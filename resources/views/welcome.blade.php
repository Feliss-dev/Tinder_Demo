<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Hinder</title>

        <!-- LOLOL -->

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>

    <body style="background-image: url(' {{asset('assets/TinderApp.webp')}}'); background-size:cover; background-attachment:fixed;" class="antialiased">
        <div class="relative min-h-screen bg-black/40 flex flex-col">
            <livewire:welcome.navigation />

            <nav x-data="{ open: false }" class="sm:sticky top-0 left-0 right-0 w-full bg-gradient-to-b from-black/90 to-black/0 pt-2">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">

                            <!-- Logo -->
                            <div class=" flex w-4 flex-shrink-0 mr-6">
                                <a href="{{ route('home') }}" wire:navigate>
                                    <x-logo width="60px" height="60px" />
                                </a>
                            </div>

                            <div class=" flex items-center text-white font-bold ml-9 text-5xl">Hinder</div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 md:flex pl-10">
                                <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                    {{ __('Products') }}
                                </x-nav-link>
                                <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                    {{ __('Learn') }}
                                </x-nav-link>
                                <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                    {{ __('Safety') }}
                                </x-nav-link>
                                <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                    {{ __('Support') }}
                                </x-nav-link>
                                <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                                    {{ __('Download') }}
                                </x-nav-link>
                            </div>

                            <!-- Actions -->
                            <div class="ml-auto items-center gap-9 hidden lg:flex">


                            </div>
                        </div>


                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                    <!-- Responsive Settings Options -->
                    <div class="mt-3 space-y-1 flex flex-col gap-5 p-2 pt-2 pb-3">
                        <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Products') }}
                        </x-nav-link>
                        <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Learn') }}
                        </x-nav-link>
                        <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Safety') }}
                        </x-nav-link>
                        <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Support') }}
                        </x-nav-link>
                        <x-nav-link class="text-xl font-bold text-white hover:text-white/95" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                            {{ __('Download') }}
                        </x-nav-link>
                    </div>
                </div>
            </nav>

            <div class="m-auto flex flex-col gap-y-10" style="text-align: center;">
                <h3 class="font-blod text-7xl sm:text-8xl text-white">
                    Swipe Right
                    <sup>
                        <span class="text-xl p-2 px-3 border-4 rounded-full border-orange-50">
                            R
                        </span>
                    </sup>
                </h3>

                @if (!auth()->check())
                    <a class="rounded-3xl bg-gradient-to-r from-pink-500 via-orange-500 to-rose-500 text-white text-xl font-bold px-8 py-2.5 max-w-fit mx-auto" href="{{route('register')}}">
                        Create an account
                    </a>
                @endif
            </div>
        </div>

        <!-- Testimonials -->
        <main class="bg-white w-full px-8 lg:px-24 py-9 mx-auto">
            <section class="grid grid-cols-2 lg:grid-cols-3 gap-5">
                @for ($i = 0; $i < 6; $i++)
                    <div class="border rounded-lg p-3 h-96 shadow overflow-hidden">
                        <div class="flex justify-between items-center">
                            <h5 class="font-bold my-3">{{fake()->name}}</h5>

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-quote w-10 h-10 text-gray-700" viewBox="0 0 16 16">
                                <path d="M12 12a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1h-1.388q0-.527.062-1.054.093-.558.31-.992t.559-.683q.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 9 7.558V11a1 1 0 0 0 1 1zm-6 0a1 1 0 0 0 1-1V8.558a1 1 0 0 0-1-1H4.612q0-.527.062-1.054.094-.558.31-.992.217-.434.559-.683.34-.279.868-.279V3q-.868 0-1.52.372a3.3 3.3 0 0 0-1.085.992 4.9 4.9 0 0 0-.62 1.458A7.7 7.7 0 0 0 3 7.558V11a1 1 0 0 0 1 1z"/>
                            </svg>
                        </div>

                        <hr class="font-bold my-2">

                        <!-- Can cause slow performance on loading -->
                        {{-- {{fake()->image}} --}}

                        <p>{{fake()->sentence(50)}}</p>
                    </div>
                @endfor
            </section>
        </main>

        @livewireScripts
    </body>
</html>
