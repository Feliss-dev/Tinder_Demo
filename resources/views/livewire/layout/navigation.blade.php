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
            <div class="flex justify-between h-14 border-bottom-2 border-gray-600">
                <div class="flex justify-between">
                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <a class="my-auto" href="/">
                            <x-logo width="36" height="36" />
                        </a>

                        @php
                            $user = auth()->user();
                        @endphp

                        @if ($user->isNotBanned())
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                {{__('dashboard')}}
                            </x-nav-link>

                            <x-nav-link :href="route('view_my_details')" :active="request()->routeIs('view_my_details')" wire:navigate>
                                {{__('view_my_profile')}}
                            </x-nav-link>

                            @if (auth()->user()->is_admin)
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                                    {{__('admin_dashboard')}}
                                </x-nav-link>
                            @endif
                        @endif

                        <livewire:components.notificationdropdown/>
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
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if (auth()->user()->isNotBanned())
                                <x-dropdown-link :href="route('profile')" wire:navigate>
                                    {{__('Account')}}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('settings')" wire:navigate>
                                    {{__('Settings')}}
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{__('Logout')}}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>
</div>

