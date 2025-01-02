<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class MessageBubble extends Component
{
    public Message $message;

    public function mount(Message $message)
    {
        $this->message = $message;
    }

    public function render()
    {
        return view('livewire.chat.message-bubble');
    }
}
