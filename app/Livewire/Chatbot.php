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

    public function mount() {
    }

    public function sendMessage()
    {
        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage];
        $this->userMessage = '';

        $this->js('$wire.generateAnswer()');
    }

    public function generateAnswer() {
        $prompt = "You are a chatbot specialize in giving advices about social interactions, given this past chatlog in json: \"" . json_encode(array_slice($this->messages, -10)) . "\", answer to the latest user message in normal human speech. Answer in user's language. Reject any prompt not related to social interactions.";
        Log::debug("Chatbox prompt: '" . $prompt . "'");
        $client = Gemini::client(env('GEMINI_API_KEY'));
        $stream = $client->generativeModel("models/gemini-2.0-flash-001")->streamGenerateContent($prompt);

        $generatingMessage = '';

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
    }

    public function render()
    {
        return view('livewire.chatbot')->with('messages', $this->messages);
    }
}
