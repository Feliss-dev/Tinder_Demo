<?php

namespace App\Livewire;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Wireable;
use Livewire\WithFileUploads;

class ChatbotMessage implements Wireable {
    public function __construct(public string $role, public ?string $text, public ?string $imageSource, public ?array $audio) {}

    public function toLivewire()
    {
        return [
            'role' => $this->role,
            'text' => $this->text,
            'imageSource' => $this->imageSource,
            'audio' => $this->audio,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['role'], $value['text'], $value['imageSource'], $value['audio']);
    }
}

class Chatbot extends Component
{
    use WithFileUploads;

    public array $messages = [];
    public $userMessage = '';
    public $userImage;

    public int $state = 0;

    public function mount() {
        $this->messages[] = new ChatbotMessage('bot', 'How can I help you?', null, null);
    }

    public function sendMessage()
    {
        $this->validate([
            'userMessage' => 'required|string',
            'userImage' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $this->messages[] = new ChatbotMessage('user', $this->userMessage, $this->userImage?->temporaryUrl(), null);
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
        )->generateContent('Suggest the most relevent category for the following user request according to these categories: ["social_question", "image_generation", "translate_previous_chatting_message", "say_something", "translate_sentences", "others"]. User request: "' . $this->messages[count($this->messages) - 1]->text . '".');

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
                // $this->state = 2;
                $this->js('$wire.answerAudioGeneration()');
                break;

            case "others":
                Log::debug("D");
                // No need to use any model, just use scripted one to reduce token count.
                $this->messages[] = new ChatbotMessage('bot', 'Invalid question.', null, null);
                $this->state = 0;
                break;
        }
    }

    public function answerTextOnlyQuestions() {
        // Use 'models/gemini-2.0-flash-001' to generate response because the response should very likely to be in pure text.

        $context = array_map(function ($message) {
            $obj = new \stdClass();
            $obj->role = $message->role;
            $obj->text = $message->text;

            return $obj;
        }, array_slice($this->messages, -10));
        $prompt = "You are a chatbot specialize in giving advices about social interactions, given this past chatlog in json: \"" . json_encode($context) . "\", continue the conversation. Answer in the same language as the latest user's message.";

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

        $this->messages[] = new ChatbotMessage('bot', $generatingMessage, null, null);
        $this->state = 0;
    }

    public function answerImageGeneration() {
        // TODO: Streaming
        $payload = [$this->messages[count($this->messages) - 1]->text];
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

        $this->messages[] = new ChatbotMessage('bot', $textPart, $imagePart, null);
        $this->state = 0;
        $this->reset('userImage');
    }

    const int SAMPLE_RATE = 24000;
    const int NUM_CHANNELS = 1;
    const int BIT_DEPTH = 16;

    public function answerAudioGeneration() {
        $geminiClient = Gemini::client(env('GEMINI_API_KEY'));

        $userInput = $this->messages[count($this->messages) - 1]->text;

        $response = $geminiClient->generativeModel("models/gemini-2.0-flash-001")->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseMimeType: Gemini\Enums\ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Gemini\Data\Schema(
                    type: Gemini\Enums\DataType::OBJECT,
                    properties: [
                        'friendly_response' => new Gemini\Data\Schema(
                            type: Gemini\Enums\DataType::STRING,
                        ),

                        'to_generate_speech' => new Gemini\Data\Schema(
                            type: Gemini\Enums\DataType::STRING,
                        ),
                    ],
                    required: ['friendly_response', 'to_generate_speech']
                )
            )
        )->generateContent("Pretent you can generate audio data from user prompt. Take this input prompt: '" . $userInput . "'. Generate a friendly chatting response like \"Sure! I can definitely help with that. Here is the text\" into property \"friendly_response\" (make some variety and have it in the same language as the input (Example: if user requested 'Translate ... to Spanish' then answer in same language as them, in this case, english)) and extract the text part to generate speech into property \"to_generate_speech\". Do not mention speech generation in the response.");

        $json = $response->json();
        $text = $json->friendly_response;

        $this->messages[] = new ChatbotMessage('bot', $text, null, [
            'finish' => false,
            'source' => null,
        ]);

        $this->state = 0;
        $this->js('$wire.generateAudio(' . (count($this->messages) - 1) . ', "' . $json->to_generate_speech . '");');
    }

    public function generateAudio(int $messageIndex, string $prompt) {
        Log::debug("Generating audio: '" . $prompt . "'.");

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

        $parts = $response->candidates[0]->content->parts;

        if (count($parts) == 0) {
            Log::error("No audio data part.");
        } else {
            $pcmData = base64_decode($response->candidates[0]->content->parts[0]->inlineData->data);

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
            $audioSource = "data:" . Gemini\Enums\MimeType::AUDIO_WAV->value . ";base64," . base64_encode($wavContent);

            $this->messages[$messageIndex]->audio = [
                'finish' => true,
                'source' => $audioSource,
            ];
        }
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}
