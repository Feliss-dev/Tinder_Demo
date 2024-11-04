<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    /**
     * Create a new event instance.
     */
      public function __construct($notification)
    {
        // Nếu `$notification` là chuỗi, chuyển đổi nó thành mảng hoặc thêm `user_id`
    $this->notification = is_array($notification) ? $notification : ['message' => $notification, 'user_id' => auth()->id()];
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new PrivateChannel('users.' .$this->notification['user_id']);
    }

    public function broadcastAs()
    {
        return 'new-notification';
    }
    public function broadcastWith()
{
    return $this->notification;
}
}
