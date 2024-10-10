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

    <body class="font-sans antialiased flex flex-col w-full h-screen overflow-hidden">
        <livewire:layout.navigation />

        <!-- Main Layout -->
        <div class="w-full flex-auto overflow-hidden grid grid-cols-4">
            <!-- Sidebar -->
            <div class="h-full flex flex-col">
                <div class="h-16 p-2.5 w-full
                            flex-shrink-0
                            bg-gradient-to-r from-[#266DD3] to-[#17BEBB]
                            flex flex-row">
                    <div class="flex flex-col items-center justify-center">
                        <x-avatar class="w-10 h-10" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />
                    </div>

                    <div class="ml-2 h-full flex-auto flex-shrink-0">
                        <p class="text-white text-lg">{{auth()->user()->name}}</p>
                    </div>
                </div>

                <div class="basis-full">
                    <livewire:components.tabs />
                </div>
            </div>

            <!-- Swiper -->
            <div class="col-span-3">
                <main class="flex-1 flex-col overflow-y-auto hidden md:flex">
                    @livewire('home')
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
