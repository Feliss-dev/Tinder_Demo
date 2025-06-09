<?php

namespace App\Livewire;

use Gemini;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Chatbot extends Component
{
    public $messages = [];
    public $userMessage = '';

    public bool $isGeneratingMessage = false;

    public ?Gemini\Client $geminiClient = null;

    public function mount() {
        $this->messages[] = ['role' => 'bot', 'text' => 'How can I help you?'];
    }

    public function sendMessage()
    {
        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage];
        $this->userMessage = '';

        $this->js('$wire.generateAnswer()');
    }

    public function generateAnswer() {
        if ($this->geminiClient == null) {
            $this->geminiClient = Gemini::client(env("GEMINI_API_KEY"));
        }

        $prompt = "You are a chatbot specialize in giving advices about social interactions, given this past chatlog in json: \"" . json_encode(array_slice($this->messages, -10)) . "\", answer to the latest user message in normal human speech. Answer in user's language. Reject any prompt not related to social interactions.";
        Log::debug("Chatbox prompt: '" . $prompt . "'");
        // $client = Gemini::client(env('GEMINI_API_KEY'));
        $stream = $this->geminiClient->generativeModel("models/gemini-2.0-flash-001")->streamGenerateContent($prompt);

        $generatingMessage = '';
        $this->isGeneratingMessage = true;

        foreach ($stream as $response) {
            $generatingMessage = $generatingMessage . $response->text();

            // Eliminate Markdown's syntax and Trim message
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

            $this->stream(to: 'generating', content: $generatingMessage, replace: true);
            sleep(0.1);
        }

        Log::debug("Chatbox response: '" . $generatingMessage . "'");

        $this->messages[] = ['role' => 'bot', 'text' => $generatingMessage];
        $this->isGeneratingMessage = false;
    }

    public function speak(int $messageIndex) {
        Log::debug("Speak message: " . $this->messages[$messageIndex]['text']);

        if ($this->geminiClient == null) {
            $this->geminiClient = Gemini::client(env("GEMINI_API_KEY"));
        }

        $stream = $this->geminiClient->generativeModel('gemini-2.5-flash-preview-tts')->withGenerationConfig(
            generationConfig: new Gemini\Data\GenerationConfig(
                responseModalities: [Gemini\Enums\ResponseModality::AUDIO],
                speechConfig: new Gemini\Data\SpeechConfig(
                    new Gemini\Data\VoiceConfig(
                        new Gemini\Data\PrebuiltVoiceConfig(voiceName: 'Kore')
                    ),
                    'en-GB'
                )
            )
        )->streamGenerateContent("Say: " . $this->messages[$messageIndex]['text']);

        foreach ($stream as $response) {
            Log::debug($response->parts());
        }
    }

    public function render()
    {
        return view('livewire.chatbot')->with('messages', $this->messages);
    }
}
