<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Chat extends Component
{
    public $chat;
    public $conversation;
    public $receiver;

    function mount($chat){
        //check auth
        abort_unless(auth()->check(),401);

        $this->chat = $chat;

        //get conversation
        $this->conversation = Conversation::findOrFail($this->chat);

        //Belong to conversation
        $belongsToConversation = auth()->user()->conversations()
                                    ->where('id', $this->conversation->id)
                                    ->exists();
        abort_unless($belongsToConversation,403);

        $this->receiver = $this->conversation->getReceiver();
    }
    #[Layout('layouts.chat')]
    public function render()
    {
        return view('livewire.chat.chat') ;
    }
}
