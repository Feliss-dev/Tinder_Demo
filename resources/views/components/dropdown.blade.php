@props(['align' => 'right', 'contentClasses' => 'py-1 bg-white'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right origin-top-left end-0';
        break;
}
@endphp

<div x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false" x-on:keydown.escape.prevent.stop="open = false;" {{ $attributes->merge(['class' => 'relative']) }}>
    <div @click="open = true">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-1000 mt-2 rounded-md shadow-lg {{ $alignmentClasses }}"
            style="display: none; z-index: 1000;"
            @click="open = false">
        <div {{ $content->attributes->class(["rounded-md ring-1 ring-black ring-opacity-5 $contentClasses"]) }}>
            {{ $content }}
        </div>
    </div>
</div>
