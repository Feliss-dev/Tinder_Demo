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
    public $chat;
    public $conversation;
    public $receiver;

    public $body;
    public $loadedMessages;
    public $paginate_var =10;

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

        #dispatch event to scroll chat to bottom
        $this->dispatch('scroll-bottom');

        #push the message
        $this->loadedMessages->push($createdMessage);

        #update the conversation model
        $this->conversation->updated_at = now();
        $this->conversation->save();

        #dispatch event
        $this->dispatch('new-message-created');

        #broadcast out message
        // $this->receiver->notify(new MessageSentNotification(auth()->user(), $createdMessage, $this->conversation));

        broadcast(new ConversationMessageSent($createdMessage, $this->conversation->id))->toOthers();
    }

    #[On('loadMore')]
    function loadMore(){
        #increment
        $this->paginate_var += 10;

        #call the loadMessages()
        $this->loadMessages();

        #dispatch event
        $this->dispatch('update-height');

    }
    function loadMessages(){

        #get count
        $count = Message::where('conversation_id', $this->conversation->id)->count();

        // skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->conversation->id)
                                            ->skip($count - $this->paginate_var)
                                            ->take($this->paginate_var)
                                            ->get();
            return $this->loadedMessages;
    }

    function deleteMatch() {
        abort_unless(auth()->check(), 401);

        //Make sure user belong to match
        $belongsToMatch = auth()->user()->matches()->where('swipe_matches.id', $this->conversation->match_id)->exists();
        abort_unless($belongsToMatch, 403);

        // Delete match
        SwipeMatch::where('id', $this->conversation->match_id)->delete();

        //Redirect
        $this->redirect(route("dashboard"), navigate: true);
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

        $this->loadMessages();
    }

    #[Layout('layouts.chat')]
    public function render() {
        return view('livewire.chat.chat') ;
    }
}
