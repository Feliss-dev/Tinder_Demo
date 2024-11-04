<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationDropdown extends Component
{
    public $notifications;
    public $unreadCount = 0;

    protected $listeners = ['newNotification' => 'refreshNotifications'];

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $this->notifications = auth()->user()->unreadNotifications()->limit(5)->get();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        auth()->user()->notifications()->find($notificationId)->markAsRead();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
