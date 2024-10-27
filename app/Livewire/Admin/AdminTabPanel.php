<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminTabPanel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('livewire.admin.admin-tab-panel');
    }
}
