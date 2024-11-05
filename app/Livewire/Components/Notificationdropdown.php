<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Notificationdropdown extends Component
{
    public $dropdownOpen = false;
    public $notifications = [];

    protected $listeners = ['newNotification' => 'addNotification', '$refresh'];

    public function mount()
    {
        // Load initial notifications (optional)
        $this->notifications = auth()->user()->notifications->take(5);
    }

    public function addNotification($notification)
    {
        // Prepend the new notification to the list
        array_unshift($this->notifications, $notification);

        // Open the dropdown when a new notification is received
        $this->dropdownOpen = true;
    }

    public function toggleDropdown()
    {
        $this->dropdownOpen = !$this->dropdownOpen;
    }
    public function render()
    {
        return view('livewire.components.notificationdropdown');
    }
}
