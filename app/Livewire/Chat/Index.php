<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('layouts.chat')]
    public function render()
    {
        return view('livewire.chat.index');
    }
}
