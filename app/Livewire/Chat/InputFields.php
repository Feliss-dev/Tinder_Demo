<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Carbon\Carbon;

class InputFields extends Component
{
    use WithFileUploads;

    protected $listeners = [ 'delete-file' => 'deleteFile', 'refresh-component' => '$refresh' ];

    public $body;
    public Conversation $conversation;
    public User $receiver;

    public $files;
    public $allFiles = [];

    public Message|null $replyingMessage = null;

    function sendMessage() {
        #check auth
        abort_unless(auth()->check(),401);

        $this->validate([
            'body' => 'nullable|string',
        ]);

        $fileLocations = [];

        # store files first.
        if (!empty($this->allFiles)) {
            foreach ($this->allFiles as $file) {
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
            'reply_id' => $this->replyingMessage?->id,
        ]);

        $this->reset('body');
        $this->reset('files');

        $this->allFiles = [];
        $this->replyingMessage = null;

        #update the conversation model
        $this->conversation->updated_at = now();
        $this->conversation->save();

        # dispatch event
        $this->dispatch('new-message-created');
        $this->dispatch('user-send-message', $createdMessage->id);

        # dispatch refresh-files event with empty array value to FileViewer so that it hide the small previewing container
        $this->dispatch('refresh-display', TemporaryUploadedFile::serializeMultipleForLivewireResponse([]));

        #broadcast out message
        broadcast(new ConversationMessageSent($createdMessage, $this->conversation->id))->toOthers();
    }

    public function updatedFiles() {
        $this->validate([
            'files.*' => 'image|max:2048',
        ]);

        $this->allFiles = array_merge($this->allFiles, $this->files);

        $serializedFile = TemporaryUploadedFile::serializeMultipleForLivewireResponse($this->allFiles);
        $this->dispatch('refresh-display', $serializedFile);
    }

    public function deleteFile(int $index) {
        array_splice($this->allFiles, $index, 1);

        $serializedFile = TemporaryUploadedFile::serializeMultipleForLivewireResponse($this->allFiles);
        $this->dispatch('refresh-display', $serializedFile);
    }

    #[On('reply-message')]
    public function reply(int $message_id) {
        $this->replyingMessage = Message::where('id', $message_id)->first();
        $this->dispatch('refresh-component');
    }

    public function render() {
        return view('livewire.chat.input-fields');
    }
}
