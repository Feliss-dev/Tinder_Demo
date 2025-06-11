<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Events\MessageDelete;
use App\Models\MessageReport;
use App\Models\MessageReportReason;
use App\Models\Swipe;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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
    public Conversation $conversation;
    public User $receiver;

    public function deleteMessage($deleteMessageID) {
        abort_unless(auth()->check(), 401);

        $message = Message::where('id', $deleteMessageID)->first();

        // Ensure the message is not from the user.
        if ($message->sender_id != auth()->id()) return;

        $message->delete_status = 1;
        $message->save();

        $this->dispatch("refresh-message.{$deleteMessageID}");
        broadcast(new MessageDelete($this->conversation->id, $deleteMessageID));
    }

    public function reportMessage($messageID, string $reasonsString, string $extra) {
        abort_unless(auth()->check(), 401);

        // reasons get passed in form of 1,2,3,4,... despite being declared as array in alpine for some reason...
        if (strlen($reasonsString) == 0) return;

        $message = Message::where('id', $messageID)->first();

        // Ensure the message exists and is not from the user.
        if (!$message || $message->sender_id == auth()->id()) return;

        try {
            $reasons = array_filter(array_map(function ($reason) {
                return (int)$reason;
            }, explode(',', $reasonsString)), function ($reasonID) {
                return MessageReportReason::where('id', $reasonID)->exists();
            });

            $report = MessageReport::create([
                'message_id' => $messageID,
                'extra' => $extra,
            ]);
            $report->reasons()->sync($reasons);
        } catch (\Exception $e) {
            Log::error($e);

            $this->dispatch("report-message-failed");
        }

        $this->dispatch("report-message-success");
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

        $this->dispatch('reload-messages');
    }

    #[Layout('layouts.chat')]
    public function render() {
        return view('livewire.chat.chat');
    }
}
