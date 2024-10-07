<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Favicon --}}
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

    <body class="font-sans antialiased flex flex-col">
        <livewire:layout.navigation />

        <div class="grid grid-cols-12 h-full w-full">
            <!-- Sidebar -->
            <div class="col-span-3">
                <!-- Display current user avatar, name, bio -->
                <div class="h-16 p-2.5 bg-gradient-to-r from-[#266DD3] to-[#17BEBB] w-full">
                    <x-avatar class="w-10 h-10" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />

                    <div class="h-full bg-red-600">
                        <p class="text-white text-lg">{{auth()->user()->name}}</p>
                        <p class="text-white text-xs whitespace-nowrap overflow-hidden text-ellipsis w-full border-black">AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAaAAAAAAA</p>
                    </div>
                </div>

                <livewire:components.tabs />
            </div>

            <div class="col-span-9">
                <main class="flex-1 flex-col overflow-y-auto hidden md:flex">
                    @livewire('home')
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
