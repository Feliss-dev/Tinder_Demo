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
    <!-- Add these to your <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>

    <style>
        .container {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;

            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        h1 {
            font-size: 2rem;
            color: #ff5864;
        }
        img {
            border-radius: 10px;
            margin: 0.5rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border:none;
            border-radius: 30px;
            color:white;
            background-color: #ff5864;
            font-size: 1rem;
            cursor: pointer;
            margin: 1rem 0.5rem;
            transition: background-color 0.3s;
        }
        .btn:hover{
            background-color: #ff404e;

        }
        .btn-secondary {
            background-color: #2ecc71;
        }
        .btn-danger {
            background-color: #e74c3c;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <livewire:layout.navigation />
    <div class="container">
        <h1>{{ $user->name }}'s Profile</h1>

        <!-- Display user information -->
        <div>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Birth Date:</strong> {{ $user->birth_date }}</p>
            <p><strong>Gender:</strong> {{ $user->gender }}</p>
            <p><strong>Bio:</strong> {{ $user->bio }}</p>
            <p><strong>Interests:</strong> {{ $user->preferences->interests ?? 'N/A' }}</p>
            <p><strong>Desired Gender:</strong> {{ $user->preferences->desired_gender ?? 'N/A' }}</p>
            <p><strong>Dating Goal:</strong> {{ $user->preferences->dating_goal ?? 'N/A' }}</p>
        </div>

        <!-- Display uploaded images -->
        <div>
            <h4>Uploaded Images: </h4>
            @php
            $images = json_decode($user->images, true); // Giải mã JSON thành mảng
        @endphp

        @if($images && count($images) > 0)
            @foreach($images as $image)
                @if(!empty($image)) <!-- Chỉ hiển thị nếu có đường dẫn ảnh -->
                <div style="display: inline-block; margin-right: 10px;">
                    <!-- Hiển thị hình ảnh nhỏ và phóng to khi click -->
                    <a href="{{ asset('storage/' . $image) }}" data-lightbox="user-gallery" data-title="Click the download button below to save the image.">
                        <img src="{{ asset('storage/' . $image) }}" alt="User Image" width="150" height="auto" style="cursor: zoom-in;">
                    </a>
                    
                </div>
                @endif
            @endforeach
        @else
            <p>No images uploaded yet.</p>
        @endif

        </div>

        <!-- Nút điều hướng -->
        <div>
            <a href=
            "{{ route('dashboard') }}"class="btn btn-secondary">BACK</a>
            <a href=
             "{{ route('dashboard') }}"
            class="btn">EDIT</a>

            <form action=
            {{-- "{{ route('delete_user', $user->id) }}" --}}
            method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your profile?');">DELETE</button>
            </form>
        </div>
    </div>
    @livewireScripts
</body>
</html>
