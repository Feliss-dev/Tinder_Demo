<div x-data="{ dropdownOpen: @entangle('dropdownOpen') }" class="relative my-4" x-init="
     let userId = {{ auth()->id() }};
    Echo.private('users.{{ auth()->id() }}')
    .listen('.new-notification', (e) => {
        Livewire.emit('newNotification', e.notification); // Gửi sự kiện đến Livewire khi nhận được thông báo
        $wire.$refresh();
    });">
    <!-- Bell Icon to Trigger the Dropdown -->
    <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block rounded-md bg-white p-2 focus:outline-none">
        <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
    </button>

    <!-- Dropdown Content -->
    <div x-show="dropdownOpen" x-on:click.outside="dropdownOpen = false" x-cloak class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20"
        style="width: 20rem;">
        <div class="py-2 max-h-72 overflow-y-auto">
            @foreach ($notifications as $notification)
                <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                    <img class="h-8 w-8 rounded-full object-cover mx-1" src="https://via.placeholder.com/40"
                        alt="avatar">
                    <p class="text-gray-600 text-sm mx-2">
                        <span class="font-bold">{{ $notification->data['sender_name'] ?? 'Admin' }}</span>
                        {{ $notification->data['message'] }}
                        <small class="block text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                    </p>
                </div>
            @endforeach
        </div>

        <!-- See All Notifications Button -->
        <a href="#" class="block bg-gray-800 text-white text-center font-bold py-2">
            See all notifications
        </a>
    </div>
</div>
