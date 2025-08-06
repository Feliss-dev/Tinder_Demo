@extends('layouts.navigation-layout')

@section('title', "View my details")

@section('content')
    @php($user = auth()->user())

    <div class="w-full">
        <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
            <div class="flex flex-col items-center">
                <div class="w-32 h-32">
                    <livewire:components.user_avatar/>
                </div>

                <h2 class="mt-4 text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
            </div>

            <h3 class="mt-8 text-xl font-semibold text-gray-700 mb-4 text-center">{{__('view_my_details.avatar.your_avatars')}}</h3>

            <livewire:components.user_avatars/>
        </div>
    </div>

    <p class="text-3xl font-bold text-center mt-4">{{__('Profile')}}</p>

    <div class="flex flex-row w-full px-16 gap-8 my-4">
        <x-user-information-panel class="flex flex-col flex-1 gap-3 bg-white shadow-lg p-4 rounded-lg" :user="$user"/>

        <div class="flex-1 bg-white shadow-lg p-4 rounded-lg">
            <livewire:components.user-image-carousel :user="$user" :editable="true"/>
        </div>
    </div>

    <div class="flex justify-center gap-2 mt-6 col-span-2 mb-4">
        <button
            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded hover:bg-blue-600 transition duration-300">
            <a href="{{ route('info.update') }}">{{__('view_my_details.profile.edit')}}</a>
        </button>

        <button class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition duration-300">
            <a href="#">{{__('view_my_details.profile.delete')}}</a>
        </button>
    </div>
@endsection
