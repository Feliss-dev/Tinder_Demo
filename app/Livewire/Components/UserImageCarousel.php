<?php

namespace App\Livewire\Components;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class UserImageCarousel extends Component
{
    public User $user;
    public bool $editable;

    public function downloadFile(int $index) {
        $image = json_decode($this->user->images, false)[$index];

        return response()->download(Storage::disk('public')->path($image));
    }

    public function render()
    {
        return view('livewire.components.user-image-carousel');
    }
}
