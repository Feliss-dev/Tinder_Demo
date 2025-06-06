<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MessageBubble extends Component
{
    protected $listeners = ['refresh-component' => '$refresh'];

    public Message $message;

    public function render()
    {
        return view('livewire.chat.message-bubble');
    }
}
