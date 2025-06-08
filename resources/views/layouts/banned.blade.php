@extends('layouts.navigation-layout')

@section('title', 'Banned')

@section('content')
    @php
        $user = auth()->user();
        $bans = $user->bans();
        $permaban = $bans->whereNull('expired_at')->first() != null;
        $expireDate = $bans->whereNotNull('expired_at')->max('expired_at');
    @endphp

    <main class="w-full h-full flex justify-center items-center bg-gray-200 shadow-lg">
        <section class="bg-white rounded-xl p-4">
            <h1 class="font-bold text-xl text-center">Suspended Notification</h1>

            <p class="mt-3">Your account has been suspended.</p>
            <p class="mt-3">If you believe this is a mistake, please contact the moderator team.</p>
        </section>
    </main>
@endsection
