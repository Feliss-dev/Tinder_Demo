<?php

namespace App\Livewire\Chat;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileViewer extends Component
{
    protected $listeners = [ 'refresh-display' => 'refreshFiles', 'refresh-component' => '$refresh' ];

    public array $files = [];

    public function refreshFiles($serializedFiles) {
        if (!TemporaryUploadedFile::canUnserialize($serializedFiles)) return;

        $this->files = TemporaryUploadedFile::unserializeFromLivewireRequest($serializedFiles);
        $this->dispatch('refresh-component');
    }

    public function deleteFile(int $fileIndex) {
        $this->dispatch('delete-file', index: $fileIndex);
    }

    public function render() {
        return view('livewire.chat.file-viewer', ['files' => $this->files]);
    }
}
