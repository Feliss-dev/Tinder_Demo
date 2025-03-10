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

<body class="font-sans antialiased flex flex-col w-full h-screen overflow-scroll mb-1 bg-[#e5e5e5]">
    @if (auth()->user()->is_admin)
        <livewire:layout.admin_navigation />
    @else
        <livewire:layout.navigation />
    @endif

    <!-- Main Layout -->
    <div class="w-full flex-auto overflow-hidden grid grid-cols-4 md:flex-row order-3 md:order-2 mt-4 md:mt-0">
        <x-app-sidebar :user="auth()->user()" />

        <!-- Swiper -->
        <div class="col-span-3 ">
            <main class="flex-1 flex-col overflow-y-auto md:flex h-full">
                @livewire('home')
            </main>

        </div>

        <livewire:chatbot />



    </div>

    @livewireScripts
</body>
</html>
