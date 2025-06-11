<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class ReceiverMessageBubble extends Component
{
    protected $listeners = ['refresh-message.{message.id}' => '$refresh'];

    public Message $message;

    public function reply() {
        $this->dispatch('reply-message', message_id: $this->message->id);
    }

    public function render()
    {
        return view('livewire.chat.receiver-message-bubble');
    }
}
