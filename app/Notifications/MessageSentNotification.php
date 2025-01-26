<?php

namespace App\Notifications;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSentNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public function __construct( public User $user, public Message $message, public Conversation $conversation)
    {
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id'=>$this->user->id,
            'message_id'=>$this->message->id,
            'conversation_id'=>$this->conversation->id,
        ];
    }
}
