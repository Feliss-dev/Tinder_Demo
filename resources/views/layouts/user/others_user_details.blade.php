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
        </div>
    </div>

    <p class="text-3xl font-bold text-center mt-4">Profile</p>

    <div class="flex flex-row w-full px-16 gap-8 my-4">
        <x-user-information-panel class="flex flex-col flex-1 gap-3 bg-white shadow-lg p-4 rounded-lg" :user="$user"/>

        <div class="flex-1 bg-white shadow-lg p-4 rounded-lg">
            <livewire:components.user-image-carousel :user="$user" :editable="false"/>
        </div>
    </div>
@endsection
