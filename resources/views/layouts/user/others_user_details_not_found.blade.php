@extends('layouts.navigation-layout')

@section('content')
    <div class="w-full h-full flex-auto overflow-hidden grid grid-cols-4 md:flex-row order-3 md:order-2 mt-4 md:mt-0">
        <x-app-sidebar/>

        <div class="col-span-3 h-full w-full overflow-x-auto flex flex-col justify-center items-center">
            <p class="text-gray-400">Well this is awkward, there is nobody here...</p>
        </div>
    </div>
@endsection
