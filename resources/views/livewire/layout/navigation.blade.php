<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
<div x-data="{ notificationSent: false }" @notification-sent.window="notificationSent = true; setTimeout(() => window.location.reload(), 3000)">
    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 border-bottom-2 border-gray-600">
                <div class="flex justify-between">
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <a class="my-auto" href="/">
                            <x-logo class="w-12 h-12" />
                        </a>

                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        <x-nav-link :href="route('view_my_details')" :active="request()->routeIs('view_my_details')" wire:navigate>
                            {{ __('View My Profile') }}
                        </x-nav-link>

                        <livewire:components.notification-bell />

                        {{-- <livewire:notification-dropdown /> --}}

                            <!-- Notification Dropdown Component -->
                {{-- <div class="flex justify-center h-screen">
                    <div x-data="{ dropdownOpen: false }" class="relative my-4"
                     x-init="
                        Echo.private(`users.${{ auth()->id() }}`)
                            .listen('NewNotification', (e) => {
                                notifications.unshift(e.notification); // Add new notification to the top of the list
                                dropdownOpen = true; // Open the dropdown automatically
                            });
                    ">


                        <!-- Bell Icon to Trigger the Dropdown -->
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative z-10 block rounded-md bg-white p-2 focus:outline-none">
                            <svg class="h-5 w-5 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                        </button>

                        <!-- Click Outside to Close the Dropdown -->
                        <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10">
                        </div>

                        <!-- Dropdown Content -->
                        <div x-show="dropdownOpen"
                            class="absolute right-0 mt-2 bg-white rounded-md shadow-lg overflow-hidden z-20"
                            style="width: 20rem;">
                            <div class="py-2 max-h-72 overflow-y-auto">
                                @foreach (auth()->user()->notifications->take(5) as $notification)
                                    <div class="flex items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2">
                                        <img class="h-8 w-8 rounded-full object-cover mx-1"
                                            src="https://via.placeholder.com/40" alt="avatar">
                                        <p class="text-gray-600 text-sm mx-2">
                                            <span
                                                class="font-bold">{{ $notification->data['sender_name'] ?? 'Admin' }}</span>
                                            {{ $notification->data['message'] }}
                                            <small
                                                class="block text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- See All Notifications Button -->
                            <a href="#"
                                class="block bg-gray-800 text-white text-center font-bold py-2">
                                See all notifications
                            </a>
                        </div>
                    </div>
                </div> --}}


                    </div>

                </div>




                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                    x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Demo notification --}}
                {{-- <div class="notifications">
                @foreach (auth()->user()->notifications as $notification)
                    <div class="notification-item">
                        <p>{{ $notification->data['message'] }}</p>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div> --}}


                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>


            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        </div>
    </nav>

</div>

