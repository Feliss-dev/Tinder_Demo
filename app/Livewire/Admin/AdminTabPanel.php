<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminTabPanel extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('livewire.admin.admin-tab-panel');
    }
}
