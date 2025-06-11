<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDelete implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public int $conversationID, public int $messageID) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel("conversation." . $this->conversationID);
    }

    public function broadcastAs(): string
    {
        return 'message-delete';
    }

    public function broadcastWith(): array
    {
        return ['message_id' => $this->messageID];
    }
}
