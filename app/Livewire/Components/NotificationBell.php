<?php

namespace App\Livewire\Components;

use Livewire\Component;

class NotificationBell extends Component
{
    public $dropdownOpen = false;
    public $notifications;

    protected $listeners = ['new-notification' => 'notificationCaptured', 'refresh-component' => '$refresh'];

    public function mount()
    {
        // Load unread notifications by order:
        // Unread notifications first in ascending order, and Read notifications in descending order.
        $this->notifications = auth()->user()->notifications()->orderBy('read_at', 'asc')->orderBy('created_at', 'desc')->get();
    }

    public function notificationCaptured($notification)
    {
        // TODO: Change to display unread counter instead of forcing open the dropdown.
        $this->notifications = auth()->user()->notifications()->orderBy('read_at', 'asc')->orderBy('created_at', 'desc')->get();
        $this->dropdownOpen = true;
        $this->dispatch('refresh-component');
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}
