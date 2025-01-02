<?php

namespace App\Livewire;

use Gemini\Laravel\Facades\Gemini;
use Livewire\Component;

class Chatbot extends Component
{
    public $messages = [];
    public $userMessage = '';

    public function sendMessage()
    {

        $this->messages[] = ['role' => 'user', 'text' => $this->userMessage];

        $response = Gemini::geminiPro()->generateContent($this->userMessage);
        $botResponse = trim(preg_replace('/\*\*(.*?)\*\*/', '$1', $response->text())) ?? 'Error: No response';

        $this->messages[] = ['role' => 'bot', 'text' => $botResponse];

        $this->userMessage = '';
    }

    public function render()
    {
        return view('livewire.chatbot')->with('messages', $this->messages);
    }
}
