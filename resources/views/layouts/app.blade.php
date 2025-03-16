@extends('layouts.navigation-layout')

@section('content')
    <div class="w-full h-full flex-auto overflow-hidden grid grid-cols-4 md:flex-row order-3 md:order-2 mt-4 md:mt-0">
        <x-app-sidebar/>

        <!-- Swiper -->
        <div class="col-span-3">
            <main class="flex-1 flex-col overflow-y-auto md:flex h-full overflow-hidden">
                <livewire:swiper.swiper/>
            </main>
        </div>
    </div>

    <livewire:chatbot />
@endsection
