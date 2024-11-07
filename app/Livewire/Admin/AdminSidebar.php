<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminSidebar extends Component
{
    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.admin.admin-sidebar');
    }
}
