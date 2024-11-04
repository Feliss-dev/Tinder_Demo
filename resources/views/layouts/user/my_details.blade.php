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

<body>
    <livewire:layout.navigation />

    <div class="wrapper">
        <div class="container">
            <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
                <!-- Hiển thị avatar hiện tại -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 relative">
                        @if (auth()->user()->activeAvatar)
                            <img class="w-full h-full rounded-full object-cover border-4 border-red-500"
                                 src="{{ asset('storage/' . auth()->user()->activeAvatar->path) }}"
                                 alt="Avatar">
                        @else
                            <img class="w-full h-full rounded-full object-cover border-4 border-red-500"
                                 src="https://via.placeholder.com/150" alt="Avatar Placeholder">
                        @endif
                    </div>
                    <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                </div>

                <!-- Form upload avatar -->
                <div class="mt-6">
                    <form action="{{ route('avatar.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                        @csrf
                        <input type="file" name="avatar" class="file-input file-input-bordered w-full max-w-xs">
                        <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            Upload Avatar
                        </button>
                    </form>
                </div>

                <!-- Hiển thị danh sách avatar -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Your Avatars</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach (auth()->user()->avatars as $avatar)
                            <div class="relative group">
                                <img class="w-full h-32 object-cover rounded-lg border-2 @if($avatar->is_active) border-red-500 @else border-gray-300 @endif"
                                     src="{{ asset('storage/' . $avatar->path) }}" alt="Avatar">

                                <!-- Chọn avatar hoặc xóa -->
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex justify-center items-center space-x-2">
                                    @if (!$avatar->is_active)
                                    <div class="flex items-center flex-col space-x-2">
                                        <a href="{{ route('avatar.setActive', $avatar->id) }}" class="bg-green-500 text-white p-2 ml-2 rounded-full hover:bg-green-600">
                                            Active
                                        </a>
                                        <form action="{{ route('avatar.destroy', $avatar->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                    @else
                                        <span class="text-white font-bold">Active</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="profile">
                <header class="mt-2">
                     Profile
                </header>

                <div class="main-info bg-gray-50 p-4 rounded-lg shadow-md">
                    <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
                    <p class="mb-2"><strong>Birth Date:</strong> {{ $user->birth_date }}</p>

                    <div class="flex flex-row">
                        <p class="mb-2 mr-4"><strong>Gender:</strong>
                            @if ($user->genders->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->genders as $gender)
                                <span class="inline-block bg-blue-100 text-blue-700 border border-blue-300 rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $gender->name }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            No data available
                            @endif
                        </p>
                    </div>

                    <p class="mb-2"><strong>Bio:</strong> {{ $user->bio }}</p>

                    <!-- Interests -->
                    <div class="flex flex-row">
                        <p class="mb-2 mr-4"><strong>Interests:</strong>
                            @if ($user->interests->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->interests as $interest)
                                    <span class="inline-block bg-green-100 text-green-700 border border-green-300 rounded-full px-3 py-1 text-sm font-semibold">
                                        {{ $interest->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                No data available
                            @endif
                        </p>
                    </div>


                    <!-- Desired Gender -->
                    <div class="flex flex-row">
                        <p class="mb-2 mr-4"><strong>Desired Gender:</strong>
                            @if ($user->desiredGenders->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->desiredGenders as $desiredGender)
                                    <span class="inline-block bg-pink-100 text-pink-700 border border-pink-300 rounded-full px-3 py-1 text-sm font-semibold">
                                        {{ $desiredGender->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                No data available
                            @endif
                        </p>
                    </div>


                    <!-- Dating Goal -->


                    <div class="flex flex-row">

                        <p class="mb-2 mr-4"><strong>Dating Goal:</strong>

                            @if ($user->datingGoals->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->datingGoals as $datingGoal)
                                    <span class="text-xl text-green-700 font-medium capitalize">
                                        {{ $datingGoal->name }} 👋
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                No data available
                            @endif
                    </p>
                    </div>


                    <!-- Languages -->
                    <div class="flex flex-row">

                        <p class="mb-2 mr-4"><strong>Languages I know:</strong>

                        @if ($user->languages->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->languages as $language)
                                <span class="inline-block bg-purple-100 text-purple-700 border border-purple-300 rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $language->name }}
                                </span>
                                @endforeach
                            </div>
                        @else
                            No data available
                        @endif
                    </p>
                    </div>

                </div>
                  <!-- AlpineJS-powered image slider and modal -->
                  <div x-data="{
                    currentSlide: 0,
                    images: [
                        @php
                            $images = json_decode($user->images, true);
                        @endphp

                        @if ($images && count($images) > 0)
                            @foreach ($images as $image)
                                @if (!empty($image))
                                    { id: {{ $loop->index }}, path: '{{ $image }}', url: '{{ asset('storage/' . $image) }}' },
                                @endif
                            @endforeach
                        @endif
                    ],
                    showModal: false,
                    selectedImage: null,

                    nextSlide() {
                        this.currentSlide = (this.currentSlide + 1) % this.images.length;
                    },
                    prevSlide() {
                        this.currentSlide = (this.currentSlide - 1 + this.images.length) % this.images.length;
                    },
                    openModal(imageUrl) {
                        this.selectedImage = imageUrl;
                        this.showModal = true;
                    },
                    closeModal() {
                        this.showModal = false;
                        this.selectedImage = null;
                    },
                    deleteImage(imagePath) {
                        if (confirm('Are you sure you want to delete this image?')) {
                            fetch('{{ route('user.image.delete', $user->id) }}', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ image: imagePath }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                this.images = this.images.filter(img => img.path !== imagePath);
                                alert(data.message);
                            })
                            .catch(error => console.error('Error:', error));
                        }
                    }
                }">
                    <template x-if="images.length === 0">
                        <p class="text-center text-gray-500 mt-4">No data available</p>
                    </template>

                    <div class="image">
                        <div class="image-slider" :style="'transform: translateX(-' + (currentSlide * 100) + '%);'">
                            <template x-for="image in images" :key="image.id">
                                <img :src="image.url" alt="User Image" @click="openModal(image.url)"
                                     width="150" height="auto" style="cursor: zoom-in;">
                            </template>
                        </div>

                        <div class="slider-controls">
                            <button @click="prevSlide">Prev</button>
                            <button @click="nextSlide">Next</button>
                        </div>

                        <button @click="deleteImage(images[currentSlide].path)"
                            class="absolute top-0 right-0 bg-red-500 text-white px-2 py-1 z-50">
                        Xóa
                        </button>
                    </div>



                    <div class="modal" x-show="showModal" @click.self="closeModal">
                        <div class="modal-content">
                            <button class="close-btn" @click="closeModal">&times;</button>
                            <img :src="selectedImage" alt="Enlarged Image">
                        </div>
                    </div>
                </div>


                <div class="button-control">
                    <button><a href="{{ route('info.update') }}">Edit Profile</a></button>
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
