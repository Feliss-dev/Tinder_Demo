<?php

namespace App\Livewire;

use App\Models\ChatbotMessage;
use Gemini;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Wireable;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\File\UploadedFile;

enum BotType : string {
    case Auto = 'auto';
    case Conversation = 'conversation';
    case ImageGeneration = 'image_gen';
    case AudioGeneration = 'audio_gen';
}

class Chatbot extends Component
{
    use WithFileUploads;

    const int LOAD_MESSAGE_PAGINATE = 10;

    public string $botType = 'conversation';
    public $userMessage = '';
    public $userImage;

    public int $state = 0;

    public Collection $loadedMessages;
    public int $loadAmount = self::LOAD_MESSAGE_PAGINATE;

    public function loadMore() {
        $this->loadAmount += self::LOAD_MESSAGE_PAGINATE;

        $this->reloadMessages();
        $this->dispatch('update-chatbot-height');
    }

    public function reloadMessages() {
        $count = auth()->user()->chatbotMessages()->count();

        // skip and query
        $this->loadedMessages = auth()->user()->chatbotMessages()
            ->skip($count - $this->loadAmount)
            ->take($this->loadAmount)
            ->get();
    }

    public function sendMessage()
    {
        abort_unless(auth()->check(), 401);

        $this->validate([
            'userMessage' => 'required|string',
            'userImage' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'botType' => 'required|in:auto,conversation,image_gen,audio_gen',
        ]);

        $imageLocation = null;
        if ($this->userImage != null) {
            $imageLocation = $this->userImage->storeAs('chatbot/' . auth()->id(), date('YmdHis', time()) . '_' . $this->userImage->getClientOriginalName(), 'public');
        }

        $this->loadedMessages->push(ChatbotMessage::create([
            'user_id' => auth()->id(),
            'message' => $this->userMessage,
            'images' => json_encode([$imageLocation]),
            'audios' => null,
            'sender' => 'user',
        ]));
        $this->loadAmount++;

        $this->reset('userMessage');
        $this->reset('userImage');

        $this->state = 1;

        switch ($this->botType) {
            case 'auto':
                $this->js('$wire.categorizeQuestionType()');
                break;

            case 'conversation':
                $this->js('$wire.answerTextOnlyQuestions()');
                break;

            case 'image_gen':
                $this->js('$wire.answerImageGeneration()');
                break;

            case 'audio_gen':
                $this->js('$wire.answerAudioGeneration()');
                break;
        }
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
                            enum: ['socialzing_question', 'image_generation', 'translate_previous_chatting_message', 'say_something', 'translate_in_text', 'translate_with_audio', 'general_question']
                        ),
                    ],
                    required: ['question_type']
                )
            )
        )->generateContent('Suggest the most relevent category for the following user request according to these categories: ["social_question", "image_generation", "translate_previous_chatting_message", "say_something", "translate_in_text", "translate_with_audio", "general_question"]. User request: "' . $this->loadedMessages[count($this->loadedMessages) - 1]->text . '". Note that asking about text or image content should returns general_question.');

        $questionType = trim((string)$response->json()->question_type);

        Log::debug("Question type: " . $questionType);

        switch ($questionType) {
            case "socialzing_question":
            case "translate_previous_chatting_message":
            case "translate_in_text":
                $this->state = 2;
                $this->js('$wire.answerTextOnlyQuestions()');
                break;

            case "image_generation":
                // $this->state = 2;
                $this->js('$wire.answerImageGeneration()');
                break;

            case "say_something":
            case "translate_with_audio":
                // $this->state = 2;
                $this->js('$wire.answerAudioGeneration()');
                break;

            case "general_question":
                // No need to use any model, just use scripted one to reduce token count.

                $this->loadedMessages->push(ChatbotMessage::create([
                    'user_id' => auth()->id(),
                    'message' => 'Sorry, I can\'t help with this question type. Maybe there are other places that can answer that.',
                    'images' => null,
                    'audios' => null,
                    'sender' => 'bot',
                    'bot_type' => 'conversation',
                ]));
                $this->loadAmount++;

                $this->state = 0;
                break;
        }
    }

    public function answerTextOnlyQuestions() {
        // Use 'models/gemini-2.0-flash-001' to generate response because the response should very likely to be in pure text.

        $conversationContext = array_map(function ($message) {
            $obj = new \stdClass();
            $obj->sender = $message->sender == 'user' ? 'A' : 'B';
            $obj->message = $message->message;

            return $obj;
        }, $this->loadedMessages->slice(-10)->all());
        $prompt = "You are a chatbot specialize in giving advices about social interactions, given this past chatlog in json: \"" . json_encode($conversationContext) . "\", now continue the conversation after the last message. Answer in the same language as user's latest message (Example: 'What's Hello World in Vietnamese', 'It means 'Chào thế giới'.'). Answer like human conversation, do not answer in formats like json.";

        Log::debug($prompt);

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

        Log::debug("Message: " . $generatingMessage);

        $this->loadedMessages->push(ChatbotMessage::create([
            'user_id' => auth()->id(),
            'message' => $generatingMessage,
            'images' => null,
            'audios' => null,
            'sender' => 'bot',
            'bot_type' => 'conversation',
        ]));
        $this->loadAmount++;

        $this->state = 0;
    }

    public function answerImageGeneration() {
        // TODO: Streaming
        $message = $this->loadedMessages[count($this->loadedMessages) - 1];
        $payload = [$message->message];

        if ($message->images != null) {
            foreach (json_decode($message->images) as $imageLocation) {
                $content = Storage::disk('public')->get($imageLocation);

                // TODO: Probably should get mime type from content.
                $payload[] = new Gemini\Data\Blob(
                    mimeType: Gemini\Enums\MimeType::from(MimeType::fromFilename($imageLocation)),
                    data: base64_encode($content),
                );
            }
        }

        $response = Gemini::client(env('GEMINI_API_KEY'))->generativeModel('models/gemini-2.0-flash-exp-image-generation')->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseModalities: [Gemini\Enums\ResponseModality::TEXT, Gemini\Enums\ResponseModality::IMAGE]
            )
        )->generateContent($payload);

        $message = null;
        $imageLocations = [];

        foreach ($response->parts() as $part) {
            if ($part->text != null) {
                $message = $part->text;
            } else if ($part->inlineData != null) {
                $path = 'chatbot/' . auth()->id() . '/' . date('YmdHis', time()) . '.' . substr($part->inlineData->mimeType->value, 6);
                Storage::disk('public')->put($path, base64_decode($part->inlineData->data));

                $imageLocations[] = $path;
            }
        }

        $this->loadedMessages->push(ChatbotMessage::create([
            'user_id' => auth()->id(),
            'message' => $message,
            'images' => json_encode($imageLocations),
            'audios' => null,
            'sender' => 'bot',
            'bot_type' => 'image_gen',
        ]));
        $this->loadAmount++;

        $this->state = 0;
    }

    const int SAMPLE_RATE = 24000;
    const int NUM_CHANNELS = 1;
    const int BIT_DEPTH = 16;

    public function answerAudioGeneration() {
        $geminiClient = Gemini::client(env('GEMINI_API_KEY'));

        $userPrompt = $this->loadedMessages[count($this->loadedMessages) - 1]->message;

        Log::debug("Generating audio...");

        $response = $geminiClient->generativeModel("models/gemini-2.0-flash-001")->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseMimeType: Gemini\Enums\ResponseMimeType::APPLICATION_JSON,
                responseSchema: new Gemini\Data\Schema(
                    type: Gemini\Enums\DataType::OBJECT,
                    properties: [
                        'friendly_response' => new Gemini\Data\Schema(
                            type: Gemini\Enums\DataType::STRING,
                        ),
                    ],
                    required: ['friendly_response']
                )
            )
        )->generateContent("Pretent you can generate audio data from user prompt. Take this input prompt: '" . $userPrompt . "'. Generate a friendly chatting response like \"Sure! I can definitely help with that. Here is the audio.\" into property \"friendly_response\" (make some variety and have it in the same language as the input (Example: if user requested 'Translate ... to Spanish' then answer in same language as them, in this case, english)). Do not mention speech generation in the response.");

        Log::debug("Response message generated.");

        $json = $response->json();
        $text = $json->friendly_response;

        $createdMessage = ChatbotMessage::create([
            'user_id' => auth()->id(),
            'message' => $text,
            'images' => null,
            'audios' => null,
            'sender' => 'bot',
            'bot_type' => 'audio_gen',
        ]);

        $this->loadedMessages->push($createdMessage);
        $this->loadAmount++;

        $this->state = 0;
        $this->js('$wire.generateAudio(' . $createdMessage->id . ', "' . addslashes($userPrompt) . '");');
    }

    public function generateAudio($messageId, string $prompt) {
        Log::debug("Generating audio, message id " . $messageId . ", prompt: " . $prompt);

        $response = Gemini::client(env('GEMINI_API_KEY'))->generativeModel('gemini-2.5-flash-preview-tts')->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseModalities: [Gemini\Enums\ResponseModality::AUDIO],
                speechConfig: new Gemini\Data\SpeechConfig(
                    new Gemini\Data\VoiceConfig(
                        new Gemini\Data\PrebuiltVoiceConfig(voiceName: 'Kore')
                    ),
                ),
            )
        )->generateContent($prompt);

        $parts = $response->candidates[0]->content->parts;

        $message = ChatbotMessage::find($messageId);

        if (count($parts) == 0) {
            $message->audios = "[]";

            $this->loadedMessages->push(ChatbotMessage::create([
                'user_id' => auth()->id(),
                'message' => "No audio generated, something went wrong.",
                'images' => null,
                'audios' => null,
                'sender' => 'bot',
                'bot_type' => 'conversation',
            ]));
            $this->loadAmount++;
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

            $path = 'chatbot/' . auth()->id() . '/' . date('YmdHis', time()) . '.wav';
            Storage::disk('public')->put($path, $wavContent);

            $message->audios = json_encode([ $path ]);
        }

        $message->save();
    }

    public function render()
    {
        $this->reloadMessages();
        return view('livewire.chatbot');
    }
}
