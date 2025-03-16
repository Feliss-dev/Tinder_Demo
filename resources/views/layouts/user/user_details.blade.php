@extends('layouts.navigation-layout')

@section('content')
    <div class="w-full">
        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
            {{-- Display the current avatar --}}
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 relative">
                    @if ($user->activeAvatar)
                        <img class="w-full h-full rounded-full object-cover border-4 border-red-500"
                             src="{{ asset('storage/' . $user->activeAvatar->path) }}"
                             alt="Avatar">
                    @else
                        <svg viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg" class="w-full h-full rounded-full border-4 border-red-500">
                            <circle r="25" cx="75" cy="50" stroke="black" stroke-width="8" fill="none" />
                            <path stroke="black" stroke-width="8" d="M0 150 C 30 70, 120 70, 150 150" fill="none" />
                        </svg>
                    @endif
                </div>
                <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
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
                    @foreach ($user->avatars as $avatar)
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
