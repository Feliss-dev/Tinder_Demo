<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class ConversationMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message, public int $conversationID) {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("conversation." . $this->conversationID);
    }

    public function broadcastAs(): string
    {
        return 'conversation-sent';
    }

    public function broadcastWith(): array
    {
        return ['message_id' => $this->message->id];
    }
}
