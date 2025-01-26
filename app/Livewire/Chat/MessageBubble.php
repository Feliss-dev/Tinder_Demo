<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MessageBubble extends Component
{
    protected $listeners = ['refresh-component' => '$refresh'];

    public Message $message;

    public function delete() {
        $this->message->delete_status = 1;
        $this->message->save();

        $this->dispatch("refresh-component");
    }

    public function reply() {
        $this->dispatch('reply-message', message_id: $this->message->id);
    }

    public function render()
    {
        return view('livewire.chat.message-bubble');
    }
}
