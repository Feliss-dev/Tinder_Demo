<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Swipe;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use App\Models\SwipeMatch;
use Livewire\Attributes\On;
use App\Models\Conversation;
use Livewire\Attributes\Layout;
use App\Notifications\MessageSentNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Chat extends Component
{
    const PAGINATE_STEP = 25;

    public $chat;
    public Conversation $conversation;
    public User $receiver;

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

    function reloadMessages() {
        #get count
        $count = Message::where('conversation_id', $this->conversation->id)->count();

        // skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->conversation->id)
                                            ->skip($count - $this->loadAmount)
                                            ->take($this->loadAmount)
                                            ->get();
    }

    public function delete($deleteMessageID) {
        $message = Message::where('id', $deleteMessageID)->first();

        // Ensure the message is from the user.
        if ($message->sender_id != auth()->id()) return;

        $message->delete_status = 1;
        $message->save();

        $this->dispatch("refresh-message.{$deleteMessageID}");
    }

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

        #mark message as read
        Message::where('conversation_id', $this->conversation->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at'=>now()]);

        #set receiver
        $this->receiver = $this->conversation->getReceiver();

        $this->reloadMessages();
    }

    #[Layout('layouts.chat')]
    public function render() {
        $this->reloadMessages();
        return view('livewire.chat.chat');
    }
}
