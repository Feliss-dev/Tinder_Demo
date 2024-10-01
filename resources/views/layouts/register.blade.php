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
        .particles {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .particles span {
            position: relative;
            width: 18px;
            height: 18px;
            margin: 6px 0;
            border-radius: 50%;
            animation: particle-movement calc(2.5s + var(--particle-index) / 10) linear infinite;
            animation-delay: calc(-3.33s + var(--particle-index) / -10.55);
        }

        .particles span:nth-child(even) {
            background: red;
            box-shadow: 0 0 20px 15px #FF00008C, 0 0 40px 30px red, 0 0 70px 45px red;
        }

        .particles span:nth-child(odd) {
            background: aqua;
            box-shadow: 0 0 20px 15px #00FFFF8C, 0 0 40px 30px aqua, 0 0 70px 45px aqua;
        }

        @keyframes particle-movement {
            0% {
                transform: translateX(-10vw) scale(1);
            }
            100% {
                transform: translateX(100vw) scale(0.1);
            }
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen
            flex flex-col sm:justify-center items-center
            pt-6 sm:pt-0
            bg-gradient-to-r from-purple-950 to-green-950">
    <div class="absolute w-screen h-screen overflow-hidden">
        <div class="particles" id="particle-container">
{{--            <span style="--particle-index: 3s;"></span>--}}
{{--            <span style="--particle-index: 2s;"></span>--}}
{{--            <span style="--particle-index: 1s;"></span>--}}
{{--            <span style="--particle-index: 5s;"></span>--}}

            <script>
                const particleContainer = document.getElementById("particle-container");

                for (let i = 0; i < 50; i++) { // Hardcoded value
                    const particle = document.createElement('span');
                    particle.style.setProperty('--particle-index', `${Math.floor(Math.random() * 50)}s`);
                    particleContainer.appendChild(particle);
                }
            </script>
        </div>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg z-0">
        {{ $slot }}
    </div>
</div>
</body>
</html>
