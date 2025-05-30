<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\View\Component;
use Illuminate\View\View;

class Avatar extends Component
{
    public function __construct(
        public User $user,
    ) {}

    public function render()
    {
        return view('components.avatar');
    }
}
