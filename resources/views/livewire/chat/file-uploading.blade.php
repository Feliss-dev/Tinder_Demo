{{--<div @class([ "border-2 border-red-600 h-48", "hidden" => count($files) == 0 ])>--}}
<div @class(['h-48', 'hidden' => empty($files)])>
    <div class="flex flex-row max-w-full h-full overflow-x-auto overflow-y-hidden whitespace-nowrap p-1 gap-1">
        {{-- Fuck this shit, I gave up midway --}}
        @foreach ($files as $file)
            {{-- TODO: Delete, Preview, Handle files that isn't image --}}
            <div class="h-full aspect-square border-2 border-gray-500 rounded-xl p-1 grid grid-rows-[5fr_1fr] grid-flow-col"
            
            >
                <img src="{{ $file->temporaryUrl()  }}" alt="{{ $file->temporaryUrl() }}" class="w-full object-fit-cover" />
                <p>{{$file->getClientOriginalName()}}</p>
            </div>
        @endforeach
    </div>
</div>
