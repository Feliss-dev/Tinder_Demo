<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class InputFields extends Component
{
    use WithFileUploads;

    public $body;
    public Conversation $conversation;
    public User $receiver;

    public $files;
    public $allFiles = [];

    function sendMessage() {
        #check auth
        abort_unless(auth()->check(),401);

        $this->validate([
            'body' => 'nullable|string',
        ]);

        $fileLocations = [];

        # store files first.
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $fileLocations[] = $file->storeAs('chat/' . $this->conversation->id, date('YmdHis', time()) . '_' . $file->getClientOriginalName(), 'public');
            }
        }

        #create message
        $createdMessage = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->receiver->id,
            'body' => $this->body,
            'files' => json_encode($fileLocations),
        ]);

        $this->reset('body');
        $this->reset('files');

        $allFiles = [];

        #update the conversation model
        $this->conversation->updated_at = now();
        $this->conversation->save();

        # dispatch event
        $this->dispatch('new-message-created');
        $this->dispatch('user-send-message', $createdMessage->id);

        $this->dispatch('upload-file', TemporaryUploadedFile::serializeMultipleForLivewireResponse([]));

        #broadcast out message
        broadcast(new ConversationMessageSent($createdMessage, $this->conversation->id))->toOthers();
    }

    public function updatedFiles() {
        $this->validate([
            'files.*' => 'image|max:2048',
        ]);

        $this->allFiles = array_merge($this->allFiles, $this->files);

        $serializedFile = TemporaryUploadedFile::serializeMultipleForLivewireResponse($this->allFiles);
        $this->dispatch('upload-file', $serializedFile);
    }

    public function render() {
        return view('livewire.chat.input-fields');
    }
}
