<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminNavbar extends Component
{
    public $searchTerm = '';

    public function submitSearch()
    {
        // Dispatch event to the UserTable component
        $this->dispatch('searchUsers', searchTerm: $this->searchTerm);
    }

    public function render()
    {
        return view('livewire.admin.admin-navbar');
    }
}
