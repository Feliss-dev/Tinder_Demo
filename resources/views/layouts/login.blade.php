<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .wave-body {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 80%;
                transform: translateX(-50%);
            }

            .wave {
                position: absolute;
                background-color: rgba(255, 255, 255, 0.15);
                width: 200%;
                height: 200%;
                border-radius: 50%;
                animation: wave-movement 10s linear infinite alternate;
            }

            .wave:nth-child(2) {
                animation-delay: -10s;
            }

            .wave:nth-child(3) {
                animation-delay: -4s;
            }

            @keyframes wave-movement {
                0% {
                    transform: translateX(-23%)
                }
                100% {
                    transform: translateX(23%);
                }
            }
        </style>
    </head>

    <body class="font-sans text-gray-900 antialiased">
        <div class="relative min-h-screen
                            flex flex-col sm:justify-center items-center
                            pt-6 sm:pt-0
                            bg-gradient-to-tr from-pink-400 to-purple-400
                            overflow-hidden">
            <div class="wave-body">
                <div class="wave"></div>
                <div class="wave"></div>
                <div class="wave"></div>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
