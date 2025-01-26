<div>
    @if ($message->sender_id == auth()->id())
        <livewire:chat.sender-message-bubble :$message />
    @else
        <livewire:chat.receiver-message-bubble :$message />
    @endif
</div>
