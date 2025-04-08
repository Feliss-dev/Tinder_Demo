<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class MessageImages extends Component
{
    public Message $message;
    public string $alignment;

    public function render()
    {
        return view('livewire.chat.message-images');
    }
}
