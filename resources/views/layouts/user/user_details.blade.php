@extends('layouts.navigation-layout')

@section('content')
    <div class="w-full">
        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
            {{-- Display the current avatar --}}
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 relative">
                    @if (auth()->user()->activeAvatar)
                        <img class="w-full h-full rounded-full object-cover border-4 border-red-500"
                             src="{{ asset('storage/' . auth()->user()->activeAvatar->path) }}"
                             alt="Avatar">
                    @else
                        <svg viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg" class="w-full h-full rounded-full border-4 border-red-500">
                            <circle r="25" cx="75" cy="50" stroke="black" stroke-width="8" fill="none" />
                            <path stroke="black" stroke-width="8" d="M0 150 C 30 70, 120 70, 150 150" fill="none" />
                        </svg>
                    @endif
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
            </div>

            {{-- Form upload avatar --}}
            <div class="mt-6">
                <form action="{{ route('avatar.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                    @csrf
                    <input type="file" name="avatar" class="file-input file-input-bordered w-full max-w-xs">
                    <button type="submit" class="mt-4 bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Upload Avatar
                    </button>
                </form>
            </div>

            {{-- Show avatar list --}}
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Your Avatars</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach (auth()->user()->avatars as $avatar)
                        <div class="relative group">
                            <img class="w-full h-32 object-cover rounded-lg border-2 @if($avatar->is_active) border-red-500 @else border-gray-300 @endif"
                                 src="{{ asset('storage/' . $avatar->path) }}" alt="Avatar">
*
                            <!-- Choose or delete avatar -->
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
    </div>

    <p class="text-3xl font-bold text-center mt-4">Profile</p>

    <div class="flex flex-row w-full px-16 gap-8 my-4">
        <x-user-information-panel class="flex flex-col flex-1 gap-3 bg-white shadow-lg p-4 rounded-lg" :user="$user"/>

        <div class="flex-1 bg-white shadow-lg p-4 rounded-lg">
            <livewire:components.user-image-carousel :user="$user" :editable="true"/>
        </div>

{{--        <div class="flex-1 bg-white shadow-lg p-4 rounded-lg">--}}
{{--            @php--}}
{{--                $images = json_decode($user->images, true);--}}
{{--                $imageCount = count($images);--}}
{{--            @endphp--}}

{{--            @if ($images && $imageCount)--}}
{{--                <div class="h-full w-full relative" x-data="{--}}
{{--                    imageIndex: 0,--}}
{{--                    images: [--}}
{{--                        @foreach ($images as $image)--}}
{{--                            @if (!empty($image))--}}
{{--                                { path: '{{$image}}', url: '{{ asset('storage/' . $image) }}' },--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    ],--}}
{{--                    modalImageIndex: -1,--}}
{{--                    impendingDeleteImageIndex: -1,--}}

{{--                    nextImage() {--}}
{{--                        this.imageIndex = (this.imageIndex + 1) % this.images.length;--}}
{{--                    },--}}
{{--                    previousImage() {--}}
{{--                        this.imageIndex = (this.imageIndex - 1 + this.images.length) % this.images.length;--}}
{{--                    },--}}
{{--                    deleteImage() {--}}
{{--                        fetch('{{ route('user.image.delete', $user->id) }}', {--}}
{{--                            method: 'DELETE',--}}
{{--                            headers: {--}}
{{--                                'Content-Type': 'application/json',--}}
{{--                                'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
{{--                            },--}}
{{--                            body: JSON.stringify({ image: this.images[this.impendingDeleteImageIndex].path }),--}}
{{--                        })--}}
{{--                        .then(response => response.json())--}}
{{--                        .then(data => {--}}
{{--                            this.images.splice(this.impendingDeleteImageIndex, 1);--}}
{{--                            alert(data.message);--}}
{{--                        })--}}
{{--                        .catch(error => console.error('Error:', error));--}}

{{--                        this.impendingDeleteImageIndex = -1;--}}
{{--                    },--}}
{{--                }">--}}
{{--                    @if ($imageCount > 1)--}}
{{--                        --}}{{-- Previous --}}
{{--                        <button type="button" class="absolute left-3 top-1/2 z-20 flex rounded-full -translate-y-1/2 bg-[#3F3F3FD0] hover:bg-[#212121D0]" x-on:click="previousImage()">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="size-10">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M 19 10 l -6 6 l 6 6" stroke="white" stroke-width="3" fill="none"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}

{{--                        --}}{{-- Next --}}
{{--                        <button type="button" class="absolute right-3 top-1/2 z-20 flex rounded-full -translate-y-1/2 bg-[#3F3F3FD0] hover:bg-[#212121D0]" x-on:click="nextImage()">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="size-10">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M 13 10 l 6 6 l -6 6" stroke="white" stroke-width="3" fill="none"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                    @endif--}}

{{--                    --}}{{-- Actions --}}
{{--                    <div class="absolute right-1 top-1 z-20 rounded-lg bg-[#3F3F3FD0] flex flex-row gap-2 p-1">--}}
{{--                        --}}{{-- Expand --}}
{{--                        <button class="size-8 bg-[#323232D0] p-1 rounded-lg" @click="modalImageIndex = imageIndex">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="white">--}}
{{--                                <path fill-rule="evenodd" d="M5.828 10.172a.5.5 0 0 0-.707 0l-4.096 4.096V11.5a.5.5 0 0 0-1 0v3.975a.5.5 0 0 0 .5.5H4.5a.5.5 0 0 0 0-1H1.732l4.096-4.096a.5.5 0 0 0 0-.707m4.344-4.344a.5.5 0 0 0 .707 0l4.096-4.096V4.5a.5.5 0 1 0 1 0V.525a.5.5 0 0 0-.5-.5H11.5a.5.5 0 0 0 0 1h2.768l-4.096 4.096a.5.5 0 0 0 0 .707"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}

{{--                        --}}{{-- Delete --}}
{{--                        <button class="size-8 bg-[#323232D0] p-1 rounded-lg" @click="impendingDeleteImageIndex = imageIndex">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -0.5 16 16" fill="white">--}}
{{--                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>--}}
{{--                                <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}
{{--                    </div>--}}

{{--                    --}}{{-- Carousel --}}
{{--                    <div class="relative min-h-[50svh] w-full overflow-hidden">--}}
{{--                        <template x-for="(image, index) in images" :key="image.path">--}}
{{--                            <div x-cloak x-show="imageIndex == index" class="absolute inset-0" x-transition.opacity.duration.500ms>--}}
{{--                                <img class="absolute w-full h-full inset-0 object-cover text-on-surface dark:text-on-surface-dark" x-bind:src="image.url" />--}}
{{--                            </div>--}}
{{--                        </template>--}}
{{--                    </div>--}}

{{--                    --}}{{-- Expand Modal --}}
{{--                    <div x-cloak x-show="modalImageIndex != -1" class="fixed top-0 left-0 w-screen h-screen bg-[#212121D0] z-[200] overflow-auto">--}}
{{--                        <button class="absolute top-2 right-2 rounded-full size-8 bg-[#1A1A1AD0] p-2" @click="modalImageIndex = -1">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="bi bi-x-lg" viewBox="0 0 16 16">--}}
{{--                                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>--}}
{{--                            </svg>--}}
{{--                        </button>--}}

{{--                        <div class="w-full h-full flex flex-row justify-center">--}}
{{--                            <img :src="images[modalImageIndex].url" alt="Modal Image" class="object-contain w-full"/>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    --}}{{-- Delete confirmation modal --}}
{{--                    <x-confirmation-modal x-cloak x-show="impendingDeleteImageIndex != -1">--}}
{{--                        <x-slot:head>--}}
{{--                            <strong class="text-black text-xl">Delete Image</strong>--}}
{{--                        </x-slot:head>--}}

{{--                        <x-slot:body>--}}
{{--                            <p class="mt-6">Are you sure you want to delete this image?</p>--}}
{{--                            <p class="font-bold">Warning: This action cannot be reverted.</p>--}}
{{--                        </x-slot>--}}

{{--                        <x-slot:actions>--}}
{{--                            <hr class="border-t-gray-400 mt-4"/>--}}

{{--                            <div class="flex flex-row-reverse gap-4 mt-4">--}}
{{--                                <button type="button" class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition duration-300" @click="deleteImage()">--}}
{{--                                    Delete Image--}}
{{--                                </button>--}}

{{--                                <button type="button" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-300" @click="impendingDeleteImageIndex = -1">--}}
{{--                                    Cancel--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </x-slot>--}}
{{--                    </x-confirmation-modal>--}}
{{--                </div>--}}
{{--            @else--}}
{{--                <div class="h-full w-full flex flex-col justify-center items-center">--}}
{{--                    <p class="text-gray-600">No image available</p>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
    </div>

    <div class="flex justify-center gap-2 mt-6 col-span-2 mb-4">
        <button class="px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-300">
            <a href="{{ route('info.update') }}">Edit Profile</a>
        </button>

        <button class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition duration-300">
            <a href="#">Delete Profile</a>
        </button>
    </div>
@endsection
