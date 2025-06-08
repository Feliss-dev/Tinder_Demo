@extends('layouts.navigation-layout')

@section('title', 'Settings')

@section('content')
    <div x-data="{ selectedTab: 'language' }" class="grid grid-cols-4 h-full">
        <section class="border-r-gray-300 border-r-2">
            <p class="font-bold mt-3 mb-3 ml-6">{{__('settings.tabs.appsettings.category')}}</p>

            <button class="admin-sidebar-tab-btn w-full" :class="selectedTab === 'language' ? 'bg-gray-200' : ''" @click="selectedTab = 'language'">
                {{__('settings.tabs.appsettings.languages')}}
            </button>
        </section>

        <section class="col-span-3">
            <section x-cloak x-show="selectedTab === 'language'" class="p-4">
                <livewire:settings.languages/>
            </section>
        </section>
    </div>
@endsection
