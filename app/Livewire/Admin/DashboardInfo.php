<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\UserImage;
use App\Models\SwipeMatch;
use Illuminate\Support\Facades\Storage;

class DashboardInfo extends Component
{
    public $users;
    public $matches;
    public $images;

    protected $listeners = ['userDeleted' => 'refreshDashboardInfo', 'userRestored' => 'refreshDashboardInfo'];


    public function mount(){
        $this->refreshDashboardInfo();
    }

    public function refreshDashboardInfo()
    {
        $this->users = User::count();
        $this->matches = SwipeMatch::count();
        $this->images = count(Storage::files('public/user_images') + Storage::files('public/avatars'));
    }

    public function render()
    {
        return view('livewire.admin.dashboard-info');
    }
}
