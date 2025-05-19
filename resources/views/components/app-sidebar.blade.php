<div class="h-full flex flex-col">
    <div class="flex-grow flex flex-col">
        @php($user = auth()->user())

        <div class="p-2.5 w-full h-16
                    bg-gradient-to-r from-[#266DD3] to-[#17BEBB]
                    flex flex-row">
            <div class="flex flex-col items-center justify-center">
                <x-avatar class="w-10 h-10 rounded-full" :user="$user"/>
            </div>

            <div class="ml-2 h-full flex-auto flex-shrink-0">
                <p class="text-white text-lg">{{$user->name}}</p>
            </div>
        </div>

        <div class="flex-grow bg-red-500 h-0">
            <livewire:components.tabs/>
        </div>
    </div>
</div>
