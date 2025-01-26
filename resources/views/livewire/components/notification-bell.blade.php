<div x-cloak x-data="{ dropdownOpen: @entangle('dropdownOpen') }" class="relative my-4"
     x-init="
    Echo.private('users.{{ auth()->id() }}').notification((notification) => {
        if (notification.type === 'system-notification' || notification.type === 'message-notification') {
            $wire.dispatch('new-notification', { notification: notification });
        }
    });
    ">

    <!-- Bell Icon to Trigger the Dropdown -->
    <button @click="dropdownOpen = !dropdownOpen" class="relative z-10 block rounded-md bg-white p-2 focus:outline-none">
        <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
        </svg>
    </button>

    <!-- Dropdown Content -->
    <div x-show="dropdownOpen" x-on:click.outside="dropdownOpen = false" class="w-[20rem] absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20 overflow-x-hidden">
        <div class="py-2 max-h-72 overflow-x-hidden overflow-y-auto">
            @foreach ($notifications as $notification)
                <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                    @if ($notification->type === 'system-notification')
                        <x-logo class="w-10 h-10" />

                        <div class="text-gray-600 text-sm mx-2">
                            <div class="flex flex-row gap-1 items-baseline">
                                <p class="font-bold text-sm">System</p>
                                <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>

                            <p>{{ $notification->data['message'] }}</p>
                        </div>
                    @else
                        {{--                        <img class="h-8 w-8 rounded-full object-cover mx-1" src="https://via.placeholder.com/40"--}}
                        {{--                             alt="avatar">--}}
                    @endif
                </div>
            @endforeach
        </div>

        <!-- See All Notifications Button -->
        <p class="block bg-gray-800 text-white text-center font-bold py-2">
            You caught up for now
        </p>
    </div>
</div>
