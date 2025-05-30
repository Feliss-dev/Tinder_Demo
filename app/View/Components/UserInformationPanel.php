<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserInformationPanel extends Component
{
    public function __construct(public User $user) {}

    public function render()
    {
        return view('components.user-information-panel');
    }
}
