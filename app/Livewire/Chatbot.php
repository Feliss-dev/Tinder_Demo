<?php

namespace App\Livewire;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class Chatbot extends Component
{
    use WithFileUploads;

    public $messages = [];
    public $userMessage = '';
    public $userImage;

    public int $state = 0;

    public function mount() {
        $this->messages[] = ['role' => 'bot', 'text' => 'How can I help you?', 'imageSource' => null, 'audioSource' => null];
    }

    public function sendMessage()
    {
        $this->validate([
            'userMessage' => 'required|string',
            'userImage' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage, 'imageSource' => $this->userImage?->temporaryUrl(), 'audioSource' => null ];
        $this->reset('userMessage');

        $this->state = 1;
        $this->js('$wire.categorizeQuestionType()');
    }

    public function categorizeQuestionType() {
        $geminiClient = Gemini::client(env("GEMINI_API_KEY"));

        $response = $geminiClient->generativeModel("models/gemini-2.0-flash-001")->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseMimeType: Gemini\Enums\ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Gemini\Data\Schema(
                    type: Gemini\Enums\DataType::OBJECT,
                    properties: [
                        'question_type' => new Gemini\Data\Schema(
                            type: Gemini\Enums\DataType::STRING,
                            enum: ['social_question', 'image_generation', 'translate_previous_chatting_message', 'say_something', 'translate_sentences', 'others']
                        ),
                    ],
                    required: ['question_type']
                )
            )
        )->generateContent('Suggest the most relevent category for the following user request according to these categories: ["social_question", "image_generation", "translate_previous_chatting_message", "say_something", "translate_sentences", "others"]. User request: "' . $this->messages[count($this->messages) - 1]['text'] . '".');

        $questionType = trim((string)$response->json()->question_type);

        Log::debug("Question type: " . $questionType);

        switch ($questionType) {
            case "social_question":
            case "translate_previous_message":
                $this->state = 2;
                $this->js('$wire.answerTextOnlyQuestions()');
                break;

            case "image_generation":
                // $this->state = 2;
                $this->js('$wire.answerImageGeneration()');
                break;

            case "say_something":
            case "translate_sentences":
                $this->state = 2;
                $this->js('$wire.answerAudioGeneration()');
                break;

            case "others":
                Log::debug("D");
                // No need to use any model, just use scripted one to reduce token count.
                $this->messages[] = ['role' => 'bot', 'text' => 'Invalid question.', 'imageSource' => null, 'audioSource' => null];   // TODO: Make a better message.
                $this->state = 0;
                break;
        }
    }

    public function answerTextOnlyQuestions() {
        // Use 'models/gemini-2.0-flash-001' to generate response because the response should very likely to be in pure text.
        // Also this model provides 1M tokens for free, way more than image generation one.

        $prompt = "You are a chatbot specialize in giving advices about social interactions, given this past chatlog in json: \"" . json_encode(array_slice($this->messages, -10)) . "\", continue the conversation. Answer in the same language as the latest message with the role 'user'.";

        $stream = Gemini::client(env('GEMINI_API_KEY'))->generativeModel("models/gemini-2.0-flash-001")->streamGenerateContent($prompt);
        $generatingMessage = '';

        foreach ($stream as $response) {
            $generatingMessage = $generatingMessage . $response->text();
            $generatingMessage = trim(preg_replace([
                '/#{1,6}(.*?)/',
                '/\*\*(.*?)\*\*/',
                '/__(.*?)__/',
                '/\*(.*?)\*/',
                '/```(.*?)```/s',
                '/\n{2,}/',
            ], [
                '$1',
                '$1',
                '$1',
                '$1',
                '$1',
                "\n",
            ], $generatingMessage));

            $this->stream(to: 'generatingMessage', content: $generatingMessage, replace: true);
            sleep(0.06);
        }

        $this->messages[] = ['role' => 'bot', 'text' => $generatingMessage, 'imageSource' => null, 'audioSource' => null];
        $this->state = 0;
    }

    public function answerImageGeneration() {
        // TODO: Streaming
        $payload = [$this->messages[count($this->messages) - 1]['text']];
        if ($this->userImage != null) {
            $payload[] = new Gemini\Data\Blob(
                mimeType: Gemini\Enums\MimeType::from($this->userImage->getMimeType()),
                data: base64_encode($this->userImage->get()),
            );
        }

        $response = Gemini::client(env('GEMINI_API_KEY'))->generativeModel('gemini-2.0-flash-preview-image-generation')->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseModalities: [Gemini\Enums\ResponseModality::TEXT, Gemini\Enums\ResponseModality::IMAGE]
            )
        )->generateContent($payload);

        $textPart = null;
        $imagePart = null;

        foreach ($response->parts() as $part) {
            if ($part->text != null) {
                $textPart = $part->text;
            } else if ($part->inlineData != null) {
                $imagePart = "data:" . $part->inlineData->mimeType->value . ";base64," . $part->inlineData->data;
            }
        }

        $this->messages[] = ['role' => 'bot', 'text' => $textPart, 'imageSource' => $imagePart, 'audioSource' => null];
        $this->state = 0;
        $this->reset('userImage');
    }

    const int SAMPLE_RATE = 24000;
    const int NUM_CHANNELS = 1;
    const int BIT_DEPTH = 16;

    public function answerAudioGeneration() {
        $prompt = $this->messages[count($this->messages) - 1]['text'];
        Log::debug("Prompt: " . $prompt);

        // TODO: Streaming
        $response = Gemini::client(env('GEMINI_API_KEY'))->generativeModel('gemini-2.5-flash-preview-tts')->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseModalities: [Gemini\Enums\ResponseModality::AUDIO],
                speechConfig: new Gemini\Data\SpeechConfig(
                    new Gemini\Data\VoiceConfig(
                        new Gemini\Data\PrebuiltVoiceConfig(voiceName: 'Kore')
                    ),
                )
            )
        )->generateContent($prompt);

        $textPart = null;
        $audioPart = null;

        Log::debug("Candidate count: " . count($response->candidates));
        Log::debug("Part count: " . count($response->parts()));

        foreach ($response->parts() as $part) {
            if ($part->text != null) {
                $textPart = $part->text;
            } else if ($part->inlineData != null) {
                $pcmData = base64_decode($part->inlineData->data);

                $wavContent = 'RIFF';
                $wavContent .= pack('V', strlen($pcmData) + 36);
                $wavContent .= 'WAVEfmt ';
                $wavContent .= pack('V', 16);
                $wavContent .= pack('v', 1);            // PCM
                $wavContent .= pack('v', static::NUM_CHANNELS);
                $wavContent .= pack('V', static::SAMPLE_RATE);
                $wavContent .= pack('V', static::SAMPLE_RATE * static::NUM_CHANNELS * static::BIT_DEPTH / 8);
                $wavContent .= pack('v', static::NUM_CHANNELS * static::BIT_DEPTH / 8);
                $wavContent .= pack('v', static::BIT_DEPTH);
                $wavContent .= 'data';
                $wavContent .= pack('V', strlen($pcmData));

                $wavContent .= $pcmData;

                $audioPart = "data:" . Gemini\Enums\MimeType::AUDIO_WAV->value . ";base64," . base64_encode($wavContent);
            }
        }

        if ($audioPart == null) {
            Log::debug("Uh oh... No Audio Part.");
        }

        $this->messages[] = ['role' => 'bot', 'text' => $textPart, 'type' => 'text', 'imageSource' => null, 'audioSource' => $audioPart];
        $this->state = 0;
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
