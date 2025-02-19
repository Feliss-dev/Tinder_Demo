<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
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

    <body class="font-sans antialiased bg-gray-100 flex flex-col min-h-screen">
        <div class="flex flex-col min-h-screen">
            <!-- Navigation -->
            <livewire:layout.navigation />

            <!-- Main Layout -->
            <div class="flex flex-1 flex-col md:flex-row">
                <!-- Sidebar -->
                <aside class="w-full md:w-1/4 bg-white shadow-md border-r hidden md:block">
                    <x-app-sidebar :user="auth()->user()" />
                </aside>

                <!-- Main Content -->
                <main class="flex-1 bg-gray-50">
                    <div class="w-full h-full">
                        @livewire('chat.chat', ['chat' => request()->chat])
                    </div>
                </main>
            </div>
        </div>
         {{-- AI chat bot --}}
         <livewire:chatbot/>
        @livewireScripts
    </body>
</html>
