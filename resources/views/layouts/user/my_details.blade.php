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
<body>
    <livewire:layout.navigation />

    <div class="wrapper">
        <div class="container">
            <div class="profile">
                <header>
                    {{ $user->name }}'s Profile
                </header>

                <div class="main-info">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Birth Date:</strong> {{ $user->birth_date }}</p>
                    <p><strong>Gender:</strong> {{ $user->gender }}</p>
                    <p><strong>Bio:</strong> {{ $user->bio }}</p>
                    <p><strong>Interests:</strong> {{ $user->preferences->interests ?? 'N/A' }}</p>
                    <p><strong>Desired Gender:</strong> {{ $user->preferences->desired_gender ?? 'N/A' }}</p>
                    <p><strong>Dating Goal:</strong> {{ $user->preferences->dating_goal ?? 'N/A' }}</p>
                </div>

                <!-- AlpineJS-powered image slider and modal -->
                <div x-data="{
                    currentSlide: 0,
                    images: [
                        @php
                            $images = json_decode($user->images, true); // Fetch images from the database
                        @endphp

                        @if($images && count($images) > 0)
                            @foreach($images as $image)
                                @if(!empty($image))
                                    { id: {{ $loop->index }}, url: '{{ asset('storage/' . $image) }}' },
                                @endif
                            @endforeach
                        @endif
                    ],
                    showModal: false,
                    selectedImage: null,
                    nextSlide() {
                        if (this.currentSlide < this.images.length - 1) {
                            this.currentSlide++;
                        } else {
                            this.currentSlide = 0;
                        }
                    },
                    prevSlide() {
                        if (this.currentSlide > 0) {
                            this.currentSlide--;
                        } else {
                            this.currentSlide = this.images.length - 1;
                        }
                    },
                    openModal(imageUrl) {
                        this.selectedImage = imageUrl;
                        this.showModal = true;
                    },
                    closeModal() {
                        this.showModal = false;
                        this.selectedImage = null;
                    }
                }">
                    <!-- Image Slider -->
                    <div class="image">
                        <div class="image-slider" :style="'transform: translateX(-' + (currentSlide * 100) + '%);'">
                            <template x-for="image in images" :key="image.id">
                                <img :src="image.url" alt="User Image" @click="openModal(image.url)" width="150" height="auto" style="cursor: zoom-in;">
                            </template>
                        </div>

                        <!-- Slider controls -->
                        <div class="slider-controls">
                            <button @click="prevSlide">Prev</button>
                            <button @click="nextSlide">Next</button>
                        </div>
                    </div>

                    <!-- Modal for image display -->
                    <div class="modal" x-show="showModal" @click.self="closeModal">
                        <div class="modal-content">
                            <button class="close-btn" @click="closeModal">&times;</button>
                            <img :src="selectedImage" alt="Enlarged Image">
                        </div>
                    </div>
                </div>

                <div class="button-control">
                    <button><a href="#">Edit Profile</a></button>
                    <button><a href="#">Delete Profile</a></button>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
<style>
    /* Basic styling adjustments for better user experience */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    .wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f0f0f0;
    }

    .container {
        width: 80%;
        max-width: 1200px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 20px;
    }

    .profile {
        display: grid;
        grid-template-areas:
            "header header"
            "main-info image"
            "button-control button-control";
        grid-template-columns: 1fr 1fr;
        grid-gap: 20px;
    }

    header {
        grid-area: header;
        font-size: 24px;
        font-weight: bold;
        color: #333;
        text-align: center;
    }

    .main-info {
        grid-area: main-info;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 30px;
    }

    .main-info p {
        font-size: 16px;
        color: #666;
    }

    /* Image slider styling */
    .image {
        grid-area: image;
        overflow: hidden;
        position: relative;
        height: 400px;
        width: 100%;
        border-radius: 8px;
    }

    .image-slider {
        display: flex;
        transition: transform 0.5s ease;
    }

    .image-slider img {
        min-width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
    }

    .slider-controls {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
        transform: translateY(-50%);
    }

    .slider-controls button {
        background-color: rgba(0, 0, 0, 0.5);
        border: none;
        padding: 10px;
        color: white;
        cursor: pointer;
    }

    .button-control {
        grid-area: button-control;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    button {
        padding: 10px 20px;
        margin-top: 20px;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        color: white;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    button a {
        color: white;
        text-decoration: none;
    }

    /* Modal styling */
    .modal {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 1000;
        overflow: hidden;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
    }

    .modal-content img {
        width: 400px;
        height: auto;
        object-fit: contain;
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ff0000;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 18px;
        border-radius: 5px;
    }

    .close-btn:hover {
        background-color: #cc0000;
    }
</style>
</html>
