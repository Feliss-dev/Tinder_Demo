<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Profile</title>
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

<body class="bg-pink-100 h-screen flex items-center justify-center ">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-3xl w-1200 h-auto text-center mt-20 overflow-y-scroll ">
        <h2 class="text-2xl font-bold italic mb-5">Account Information</h2>
        <form action="{{ route('info.update') }}" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-2 gap-6">
            @csrf

            <!-- Name -->
            <div class="flex flex-col">
                <label for="name" class="font-bold mb-1 text-left">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                    required class="p-2 border border-gray-300 rounded">
            </div>

            <!-- Dropdown for Languages -->
            <div class="flex flex-col">
                <label for="languages" class="font-bold mb-1 text-left">Languages you know</label>
                <div class="" x-data="{ open: false }" class="relative">
                    <div @click="open = !open"
                        class=" bg-white-100 border rounded border-gray-300 p-2 cursor-pointer text-left ">Select
                        Languages</div>
                    <div x-show="open" @click.away="open = false"
                        class="dropdown-menu bg-white border border-gray-300 absolute w-50 max-h-48 overflow-y-auto mt-1 z-10">
                        @foreach ($languages as $language)
                            <label class="dropdown-item flex flex-row text-center items-center p-2">
                                <input type="checkbox" name="languages[]" value="{{ $language->id }}"
                                    {{ in_array($language->id, $user->languages->pluck('id')->toArray()) ? 'checked' : '' }}
                                    class="mr-2">
                                {{ $language->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Date of Birth -->
            <div class="flex flex-col">
                <label for="birth_date" class="font-bold mb-1 text-left">Date of Birth</label>
                <input type="date" id="birth_date" name="birth_date"
                    value="{{ old('birth_date', Auth::user()->birth_date) }}" required
                    class="p-2 border border-gray-300 rounded">
            </div>

            <!-- Gender -->
<div class="flex flex-col">
    <label for="gender" class="font-bold mb-1 text-left">My Gender</label>
    <div class="relative" x-data="{ open: false }">
        <div @click="open = !open"
            class="bg-white-100 border rounded border-gray-300 p-2 cursor-pointer text-left">Gender</div>
        <div x-show="open" @click.away="open = false"
            class="dropdown-menu bg-white border border-gray-300 absolute w-auto max-h-48 overflow-y-auto mt-1 z-10">
            @foreach ($genders as $gender)
                <label class="flex items-center px-2 py-4">
                    <input type="radio" name="gender" value="{{ $gender->id }}"
                        {{ $user->gender_id == $gender->id ? 'checked' : '' }} class="mr-2">
                    {{ $gender->name }}
                </label>
            @endforeach
        </div>
    </div>
</div>

            <!-- Bio -->
            <div class="flex flex-col col-span-2">
                <label for="bio" class="font-bold mb-1 text-left">Bio</label>
                <textarea id="bio" name="bio" class="p-2 border border-gray-300 rounded">{{ old('bio', Auth::user()->bio) }}</textarea>
            </div>

            <!-- Images -->
            <div class="flex flex-col">
                <label for="images" class="font-bold mb-1 text-left">Upload Images</label>
                <input type="file" id="images" name="images[]" multiple
                    class="p-2 border border-gray-300 rounded">
            </div>

            <!-- Interests -->
            <div class="flex flex-col">
                <label for="interests" class="font-bold mb-1 text-left">Interests</label>
                <div class="relative" x-data="{ open: false }">
                    <div @click="open = !open"
                        class="bg-white-100 border rounded border-gray-300 p-2 cursor-pointer text-left">
                        Select Interests
                    </div>
                    <div x-show="open" @click.away="open = false"
                        class="dropdown-menu bg-white border border-gray-300 absolute w-auto max-h-48 overflow-y-auto mt-1 z-10">
                        @foreach ($interests as $interest)
                            <label class="dropdown-item flex flex-row text-center items-center p-2">
                                <input type="checkbox" name="interests[]" value="{{ $interest->id }}"
                                    {{ in_array($interest->id, $user->interests->pluck('id')->toArray()) ? 'checked' : '' }}
                                    class="mr-2">
                                {{ $interest->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>


           <!-- Desired Gender -->
<div class="flex flex-col">
    <label for="desired_gender" class="font-bold mb-1 text-left">My Desired Gender</label>
    <div class="relative" x-data="{ open: false }">
        <div @click="open = !open"
            class="bg-white-100 border rounded border-gray-300 p-2 cursor-pointer text-left">Desired Gender</div>
        <div x-show="open" @click.away="open = false"
            class="dropdown-menu bg-white border border-gray-300 absolute w-auto max-h-48 overflow-y-auto mt-1 z-10">
            @foreach ($desiredGenders as $desiredGender)
                <label class="flex items-center px-2 py-4">
                    <input type="radio" name="desiredGender" value="{{ $desiredGender->id }}"
                        {{ $user->desired_gender_id == $desiredGender->id ? 'checked' : '' }} class="mr-2">
                    {{ $desiredGender->name }}
                </label>
            @endforeach
        </div>
    </div>
</div>

            <!-- Dating Goal -->
<div class="flex flex-col">
    <label for="dating_goal" class="font-bold mb-1 text-left">My Dating Goal</label>
    <div class="relative" x-data="{ open: false }">
        <div @click="open = !open"
            class="bg-white-100 border rounded border-gray-300 p-2 cursor-pointer text-left">Dating Goal</div>
        <div x-show="open" @click.away="open = false"
            class="dropup-menu bg-white border border-gray-300 absolute w-50 max-h-48 overflow-y-auto mt-1 z-10">
            @foreach ($datingGoals as $datingGoal)
                <label class="flex items-center px-2 py-4">
                    <input type="radio" name="datingGoal" value="{{ $datingGoal->id }}"
                        {{ $user->dating_goal_id == $datingGoal->id ? 'checked' : '' }} class="mr-2">
                    {{ $datingGoal->goal_name }}
                </label>
            @endforeach
        </div>
    </div>
</div>

            <!-- Submit Button -->
            <button type="submit"
                class="col-span-2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg mt-4">Update Profile</button>
        </form>
    </div>
    @livewireScripts
</body>



</html>
