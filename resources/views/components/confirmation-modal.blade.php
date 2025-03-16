@props([
    'head',
    'body',
    'actions'
])

<div {{ $attributes->merge(['class' => 'fixed top-0 left-0 w-screen h-screen bg-[#212121D0] z-[200] overflow-auto flex flex-col justify-center items-center']) }}>
    <div class="rounded-xl bg-white p-6">
        {{$head}}

        @if ($body->hasActualContent())
            {{$body}}
        @endif

        {{$actions}}
    </div>
</div>
