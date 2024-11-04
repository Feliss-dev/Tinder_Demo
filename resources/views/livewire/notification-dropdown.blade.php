<div class="relative" x-data="{ open: false }" x-init="Echo.private('users.{{ auth()->id() }}')
    .listen('new-notification', (e) => {
        Livewire.emit('notificationReceived', e);
    });">
    <button @click="open = !open" class="relative bg-white p-2 rounded-md">
        <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
        @if ($this->$unreadCount > 0)
            <span
                class="absolute top-0 right-0 inline-block w-4 h-4 bg-red-600 text-white text-xs text-center rounded-full">
                {{ $this->unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-md shadow-lg w-80">
        <div class="py-2 max-h-72 overflow-y-auto">
            @forelse ($this->notifications as $notification)
                <div class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <p class="text-gray-600 text-sm">
                        <strong>{{ $notification->data['sender_name'] ?? 'Admin' }}:</strong>
                        {{ $notification->data['message'] }}
                        <br>
                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                    </p>
                    <button wire:click="markAsRead('{{ $notification->id }}')" class="ml-auto text-blue-500">
                        Mark as read
                    </button>
                </div>
            @empty
                <p class="text-gray-600 text-center py-4">No new notifications.</p>
            @endforelse
        </div>
        <a href="#" class="block bg-gray-800 text-white text-center font-bold py-2">See all notifications</a>
    </div>
</div>
