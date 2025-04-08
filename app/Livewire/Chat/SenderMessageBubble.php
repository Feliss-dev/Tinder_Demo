<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SenderMessageBubble extends Component
{
    public Message $message;

    protected $listeners = ['refresh-message.{message.id}' => '$refresh'];

    public function reply() {
        $this->dispatch('reply-message', message_id: $this->message->id);
    }

    public function render()
    {
        return view('livewire.chat.sender-message-bubble');
    }
}
