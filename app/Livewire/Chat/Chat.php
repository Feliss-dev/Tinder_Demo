<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use Log;
use App\Models\Swipe;
use App\Models\Message;
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
    public $conversation;
    public $receiver;

    public $loadedMessages;
    public $loadAmount = self::PAGINATE_STEP;

    protected $listeners = [ 'user-send-message' => 'userSendMessage' ];

    function listenBroadcastedMessage(int $messageID){
        $this->dispatch('scroll-bottom');

        $newMessage = Message::find($messageID);

        #push messagge
        $this->loadedMessages->push($newMessage);

        #mark message as read
        $newMessage->read_at = now();
        $newMessage->save();

        #refresh chatlist
        $this->dispatch('new-message-created');
    }

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
