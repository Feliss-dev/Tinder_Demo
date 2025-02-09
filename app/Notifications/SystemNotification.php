<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public function __construct(public string $message, public int $userId)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            "message" => $this->message,
            "user_id" => $this->userId,
        ];
    }

    public function broadcastType() : string
    {
        return 'system-notification';
    }

    public function databaseType(object $notifiable): string
    {
        return 'system-notification';
    }
}
