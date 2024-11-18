{{--<div @class([ "border-2 border-red-600 h-48", "hidden" => count($files) == 0 ])>--}}
<div class="border-2 border-red-600 h-48">
    <div class="flex flex-row max-w-full h-full overflow-x-auto overflow-y-hidden whitespace-nowrap p-1">
        @foreach ($files as $file)
            <div class="h-full aspect-square">
                <p>{{$file->getClientOriginalName()}}</p>
            </div>
        @endforeach
    </div>
</div>
