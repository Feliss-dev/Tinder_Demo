<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class SenderMessageBubble extends Component
{
    protected $listeners = ['refresh-component' => '$refresh'];

    public Message $message;

    public function delete() {
        $this->message->delete_status = 1;
        $this->message->save();

        $this->dispatch("refresh-component");
    }

    public function render()
    {
        return view('livewire.chat.sender-message-bubble');
    }
}
