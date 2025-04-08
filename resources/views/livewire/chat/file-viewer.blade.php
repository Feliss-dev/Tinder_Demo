<div @class(['h-48', 'hidden' => empty($files)])>
    <div class="flex flex-row max-w-full h-full overflow-x-auto overflow-y-hidden whitespace-nowrap p-1 gap-2">
        @foreach ($files as $index => $file)
            {{-- TODO: Handle files that isn't image --}}
            <div class="relative h-full aspect-square border-2 border-gray-500 rounded-xl p-1 grid-flow-col" x-data="{ displayExtra: false }" @mouseleave="displayExtra = false;" @mouseover="displayExtra = true;">
                <div class="w-full h-full overflow-hidden flex flex-col">
                    <div class="overflow-hidden" style="flex: 1 1 auto">
                        <img src="{{$file->temporaryUrl()}}" alt="{{$file->getClientOriginalName()}}}" class="h-full mx-auto object-cover" style="flex: 0 1 auto" />
                    </div>
                    <p style="flex: 0 1 1rem">{{$file->getClientOriginalName()}}</p>
                </div>

                <span x-show="displayExtra" class="bg-white border-2 border-gray-500 rounded-md absolute -top-1.5 -right-2.5 p-1">
                    <button type="button" class="block" wire:click="deleteFile({{$index}})">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                        </svg>
                    </button>
                </span>
            </div>
        @endforeach
    </div>
</div>
