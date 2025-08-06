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

    public ?string $body = null;
    public Conversation $conversation;
    public User $receiver;

    public ?Message $replyingMessage = null;
    public array $images = [];

    function sendMessage() {
        #check auth
        abort_unless(auth()->check(),401);
        abort_unless(!auth()->user()->isBanned(),401);

        $this->validate([
            'body' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,jpg,png|max:2048',  // TODO: Dynamically read the ini variable to determine size.
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $file) {
                $response = Http::post(env("IMAGE_DETECTOR_URL"), [
                    'image_content' => base64_encode($file->get()),
                ]);

                if ($response->ok()) {
                    $jsonResponse = $response->json();

                    if ($jsonResponse['label'] == 'cat') {
                        $this->resetMessaging();
                        $this->dispatch('image-validation-forbidden');
                        return;
                    }
                } else {
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
            'temporary_files' => TemporaryUploadedFile::serializeMultipleForLivewireResponse($this->images),
            'reply_id' => $this->replyingMessage?->id,
        ];

        $this->resetMessaging();

        $this->dispatch('user-send-message', $payload);
    }

    private function resetMessaging() {
        $this->reset('body');
        $this->reset('images');

        $this->replyingMessage = null;
    }

    public function broadcastMessage($messageId) {
        broadcast(new ConversationMessageSent(Message::where('id', $messageId)->first(), $this->conversation->id))->toOthers();
    }

    #[On('reply-message')]
    public function reply(int $message_id) {
        $this->replyingMessage = Message::where('id', $message_id)->first();
    }

    public function closeReplyingMessage() {
        $this->replyingMessage = null;
    }

    public function render() {
        return view('livewire.chat.input-fields');
    }
}
