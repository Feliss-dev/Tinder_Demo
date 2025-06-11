<?php

namespace App\Livewire\Chat;

use App\Events\ConversationMessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ConversationContainer extends Component
{
    const PAGINATE_STEP = 25;

    public Conversation $conversation;
    public User $receiver;

    public $loadedMessages;
    public int $loadAmount = self::PAGINATE_STEP;

    public array $uploadingMessages = [];

    #[On('echo-private:conversation.{conversation.id},.conversation-sent')]
    function listenBroadcastedMessage($event) {
        $this->dispatch('scroll-bottom');

        $newMessage = Message::find($event['message_id']);

        #push message
        $this->loadedMessages->push($newMessage);

        #mark message as read
        $newMessage->read_at = now();
        $newMessage->save();

        #refresh chatlist
        $this->dispatch('new-message-created');
    }

    #[On('echo-private:conversation.{conversation.id},.message-delete')]
    #[Renderless]
    public function messageDelete($event) {
        $this->dispatch("refresh-message.{$event['message_id']}");
    }

    #[On('user-send-message')]
    public function userSendMessage(array $payload) {
        $this->uploadingMessages[] = $payload;
        $this->dispatch('scroll-bottom');
        $this->js('$wire.uploadLatestMessage("' . $payload['created_at'] . '")');
    }

    public function uploadLatestMessage($timeId) {
        $payload = null;

        foreach ($this->uploadingMessages as $message) {
            if ($message['created_at'] == $timeId) {
                $payload = $message;
                break;
            }
        }

        if ($payload != null) {
            $deserializedTemporaryFiles = TemporaryUploadedFile::unserializeFromLivewireRequest($message['temporary_files']);
            $fileLocations = array_map(function($file) {
                return $file->storeAs('chat/' . $this->conversation->id, date('YmdHis', time()) . '_' . $file->getClientOriginalName(), 'public');
            }, $deserializedTemporaryFiles);

            #create message
            $createdMessage = Message::create([
                'created_at' => $timeId,
                'conversation_id' => $this->conversation->id,
                'sender_id' => auth()->id(),
                'receiver_id' => $payload['receiver_id'],
                'body' => $payload['body'],
                'files' => json_encode($fileLocations),
                'reply_id' => $payload['reply_id'],
            ]);

            $this->conversation->updated_at = now();
            $this->conversation->save();

            $this->dispatch('new-message-created');

            broadcast(new ConversationMessageSent($createdMessage, $this->conversation->id))->toOthers();
        }

        $this->uploadingMessages = array_filter($this->uploadingMessages, function($message) use ($timeId) {
            return $message['created_at'] !== $timeId;
        });
    }

//    #[On('user-send-message')]
//    function userSendMessage(int $messageId) {
//        #push the message
//        $this->loadedMessages->push(Message::where('id', $messageId)->first());
//
//        #scroll bottom
//        $this->dispatch('scroll-bottom');
//    }

    #[On('loadMore')]
    function loadMore(){
        $this->loadAmount += self::PAGINATE_STEP;

        $this->reloadMessages();
        $this->dispatch('update-height');
    }

    #[On('reload-messages')]
    function reloadMessages() {
        #get count
        $count = Message::where('conversation_id', $this->conversation->id)->count();

        // skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->conversation->id)
            ->skip($count - $this->loadAmount)
            ->take($this->loadAmount)
            ->get();
    }

    public function render() {
        $this->reloadMessages();
        return view('livewire.chat.conversation-container');
    }
}
