<?php

namespace App\Livewire\Chat;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class FilesPreview extends Component
{
    public $files;

    protected $listeners = ['file-added' => 'logtest'];

    public function logtest() {
        Log::debug('File Inserted.');
    }

    public function render()
    {
        return view('livewire.chat.files-preview');
    }
}
