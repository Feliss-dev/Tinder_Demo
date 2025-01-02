<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Gemini\Laravel\Facades\Gemini;

class Chatbot extends Component
{
    public $messages = [];
    public $userMessage = '';

    public function sendMessage()
    {

        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage];

        $response = Gemini::geminiPro()->generateContent($this->userMessage);
        $botResponse = $response->text() ?? 'Error: No response';
        $this->messages[] = ['role' => 'bot', 'text' => $botResponse];

        $this->userMessage = '';
    }

    public function render()
    {
        return view('livewire.chatbot')->with('messages', $this->messages);
    }
}
