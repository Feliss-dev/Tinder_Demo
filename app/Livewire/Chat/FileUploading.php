<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploading extends Component
{
    protected $listeners = [ 'upload-file' => 'uploadFile', 'refreshComponent' => '$refresh' ];

    public $files = [];

    public function uploadFile($serializedFile) {
        if (!TemporaryUploadedFile::canUnserialize($serializedFile)) return;

        $this->files = array_merge($this->files, TemporaryUploadedFile::unserializeFromLivewireRequest($serializedFile));
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.chat.file-uploading', ['files' => $this->files]);
    }
}
