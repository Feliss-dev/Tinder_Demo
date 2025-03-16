<?php

namespace App\Livewire\Components;

use App\Models\User;
use Livewire\Component;

class UserImageCarousel extends Component
{
    public User $user;
    public bool $editable;

    public function render()
    {
        return view('livewire.components.user-image-carousel');
    }
}
