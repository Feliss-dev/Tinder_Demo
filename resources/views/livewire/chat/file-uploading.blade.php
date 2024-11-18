<div @class([ "h-48 border-2 border-red-600" => count($files) > 0 ])>
    <div class="h-full flex flex-row">
        @foreach ($files as $file)
            <p>{{$file->getClientOriginalName()}}</p>
        @endforeach
    </div>
</div>
