<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploading extends Component
{
    protected $listeners = [ 'upload-file' => 'uploadFile', 'refreshComponent' => '$refresh' ];

    public $files = [];
    public $existingFiles = [];

    public function mount(){
        $this->existingFiles = [];
    }

    public function uploadFile($serializedFiles) {
        if (!TemporaryUploadedFile::canUnserialize($serializedFiles)) return;

        $uploadedFiles = TemporaryUploadedFile::unserializeFromLivewireRequest($serializedFiles);

        $this->files = collect($uploadedFiles)->filter(function ($file){
            return !in_array($file->getClientOriginalName(), $this->existingFiles);
        })->toArray();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.chat.file-uploading', ['files' => $this->files]);
    }
}
