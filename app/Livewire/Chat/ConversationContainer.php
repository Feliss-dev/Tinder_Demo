<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Attributes\On;
use Livewire\Component;

class ConversationContainer extends Component
{
    const PAGINATE_STEP = 25;

    public Conversation $conversation;

    public $loadedMessages;
    public int $loadAmount = self::PAGINATE_STEP;

    #[On('echo-private:conversation.{conversation.id},.conversation-sent')]
    function listenBroadcastedMessage($event) {
        $this->dispatch('scroll-bottom');

        $newMessage = Message::find($event['message_id']);

        #push message
        $this->loadedMessages->push($newMessage);

        #mark message as read
        $newMessage->read_at = now();
        $newMessage->save();

        #refresh chatlist
        $this->dispatch('new-message-created');
    }

    #[On('user-send-message')]
    function userSendMessage(int $messageId) {
        #push the message
        $this->loadedMessages->push(Message::where('id', $messageId)->first());

        #scroll bottom
        $this->dispatch('scroll-bottom');
    }

    #[On('loadMore')]
    function loadMore(){
        $this->loadAmount += self::PAGINATE_STEP;

        $this->reloadMessages();
        $this->dispatch('update-height');
    }

    #[On('reload-messages')]
    function reloadMessages() {
        #get count
        $count = Message::where('conversation_id', $this->conversation->id)->count();

        // skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->conversation->id)
            ->skip($count - $this->loadAmount)
            ->take($this->loadAmount)
            ->get();
    }

    public function render() {
        $this->reloadMessages();
        return view('livewire.chat.conversation-container');
    }
}
