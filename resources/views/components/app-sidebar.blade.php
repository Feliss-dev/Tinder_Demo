<div class="h-full flex flex-col ">
    <div class="h-16 p-2.5 w-full
                            flex-shrink-0
                            bg-gradient-to-r from-[#266DD3] to-[#17BEBB]
                            flex flex-row">
        <div class="flex flex-col items-center justify-center">
            <x-avatar class="w-10 h-10" src="{{$user->images}}" alt="{{$user->name}}" />
        </div>

        <div class="ml-2 h-full flex-auto flex-shrink-0">
            <p class="text-white text-lg">{{$user->name}}</p>
        </div>
    </div>

    <div class="basis-full ">
        <livewire:components.tabs />
    </div>
</div>
