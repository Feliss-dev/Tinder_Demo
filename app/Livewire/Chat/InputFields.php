<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class InputFields extends Component
{
    public $body;
    public Conversation $conversation;
    public User $receiver;

    function sendMessage() {
        #check auth
        abort_unless(auth()->check(),401);
        $this->validate(['body'=>'required|string']);

        #create message
        $createdMessage = Message::create([
            'conversation_id'=>$this->conversation->id,
            'sender_id'=>auth()->id(),
            'receiver_id'=>$this->receiver->id,
            'body'=>$this->body
        ]);

        $this->reset('body');

        #update the conversation model
        $this->conversation->updated_at = now();
        $this->conversation->save();

        #dispatch event
        $this->dispatch('new-message-created');
        $this->dispatch('user-send-message', $createdMessage->id);

        #broadcast out message
        broadcast(new ConversationMessageSent($createdMessage, $this->conversation->id))->toOthers();
    }

    public function render()
    {
        return view('livewire.chat.input-fields');
    }
}
