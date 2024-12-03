<?php

namespace App\Livewire\Chat;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploading extends Component
{
    protected $listeners = [ 'upload-file' => 'uploadFile', 'refreshComponent' => '$refresh' ];

    public array $files = [];

    public function uploadFile($serializedFiles) {
        if (!TemporaryUploadedFile::canUnserialize($serializedFiles)) return;

        $this->files = TemporaryUploadedFile::unserializeFromLivewireRequest($serializedFiles);
        $this->dispatch('refreshComponent');
    }

    public function deleteFile(int $fileIndex) {
        array_splice($this->files, $fileIndex, 1);
        $this->dispatch('refreshComponent');
    }

    public function render() {
        return view('livewire.chat.file-uploading', ['files' => $this->files]);
    }
}
