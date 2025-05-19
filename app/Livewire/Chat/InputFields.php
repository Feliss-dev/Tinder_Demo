<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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

    public $files = [];
    public $allFiles = [];

    public Message|null $replyingMessage = null;

    function sendMessage() {
        #check auth
        abort_unless(auth()->check(),401);

        $this->validate([
            'body' => 'nullable|string',
        ]);

        if (!empty($this->allFiles)) {
            foreach ($this->allFiles as $file) {
                $response = Http::post(env("IMAGE_DETECTOR_URL"), [
                    'image_content' => base64_encode($file->get()),
                ]);

                if ($response->ok()) {
                    $jsonResponse = $response->json();

                    if ($jsonResponse['label'] == 'cat') {
                        Log::debug("Forbidden");

                        $this->resetMessaging();
                        $this->dispatch('image-validation-forbidden');
                        return;
                    }
                } else {
                    Log::debug("Failed");

                    $this->resetMessaging();
                    $this->dispatch('image-validation-failed');
                    return;
                }
            }
        }

        $payload = [
            'created_at' => date("Y-m-d H:i:s"),
            'receiver_id' => $this->receiver->id,
            'body' => $this->body,
            'temporary_files' => TemporaryUploadedFile::serializeMultipleForLivewireResponse($this->allFiles),
            'reply_id' => $this->replyingMessage?->id,
        ];

        $this->resetMessaging();

        $this->dispatch('user-send-message', $payload);
    }

    private function resetMessaging() {
        $this->reset('body');
        $this->reset('files');

        $this->allFiles = [];
        $this->replyingMessage = null;
        $this->dispatch('refresh-display', TemporaryUploadedFile::serializeMultipleForLivewireResponse([]));
    }

    public function broadcastMessage($messageId) {
        broadcast(new ConversationMessageSent(Message::where('id', $messageId)->first(), $this->conversation->id))->toOthers();
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
