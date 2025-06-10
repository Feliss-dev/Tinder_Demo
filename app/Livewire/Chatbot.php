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
        $this->messages[] = ['role' => 'bot', 'text' => 'How can I help you?', 'type' => 'text', 'imageSource' => null];
    }

    public function sendMessage()
    {
        $this->validate([
            'userMessage' => 'required|string',
            'userImage' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage, 'type' => 'text', 'imageSource' => $this->userImage?->temporaryUrl() ];
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
                            enum: ['social_question', 'image_generation', 'translate_previous_chatting_message', 'translate_sentences', 'others']
                        ),
                    ],
                    required: ['question_type']
                )
            )
        )->generateContent('Categorize this question: "' . $this->messages[count($this->messages) - 1]['text'] . '" according to one of these class: ["social_question", "image_generation", "translate_previous_chatting_message", "translate_sentences", "others"].');

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

            case "localization":
                // TODO: Work on this once the library google-gemini-php/client got PCM MIMETYPE
                Log::debug("C");
                break;

            case "others":
                Log::debug("D");
                // No need to use any model, just use scripted one to reduce token count.
                $this->messages[] = ['role' => 'bot', 'text' => 'Invalid question.', 'type' => 'text', 'imageSource' => null];   // TODO: Make a better message.
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

        $this->messages[] = ['role' => 'bot', 'text' => $generatingMessage, 'type' => 'text', 'imageSource' => null];
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

        $this->messages[] = ['role' => 'bot', 'text' => $textPart, 'type' => 'text', 'imageSource' => $imagePart];
        $this->state = 0;
        $this->reset('userImage');
    }

    public function speak(int $messageIndex) {
//        $stream = $this->geminiClient->generativeModel('gemini-2.5-flash-preview-tts')->withGenerationConfig(
//            generationConfig: new Gemini\Data\GenerationConfig(
//                responseModalities: [Gemini\Enums\ResponseModality::AUDIO],
//                speechConfig: new Gemini\Data\SpeechConfig(
//                    new Gemini\Data\VoiceConfig(
//                        new Gemini\Data\PrebuiltVoiceConfig(voiceName: 'Kore')
//                    ),
//                    'en-GB'
//                )
//            )
//        )->streamGenerateContent("Say: " . $this->messages[$messageIndex]['text']);
//
//        foreach ($stream as $response) {
//            Log::debug($response->parts());
//        }
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
