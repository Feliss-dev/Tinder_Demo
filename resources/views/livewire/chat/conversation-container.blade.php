<div id="conversation-container">
    @foreach ($loadedMessages as $message)
        {{-- Fuck Livewire --}}
        <livewire:chat.message-bubble :$message :key="$message->id"/>
    @endforeach

    @if (count($loadedMessages) !== 0)
        @php
            $lastMessage = $loadedMessages[count($loadedMessages) - 1];
        @endphp

        <p @class(['text-xs text-gray-500', 'ml-auto' => $lastMessage->sender_id == auth()->id()])>Sent at {{ $lastMessage->created_at }}</p>
    @endif
</div>
