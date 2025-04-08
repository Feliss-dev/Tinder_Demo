@php
$displayImages = array_slice(json_decode($images, true), 0, 4, true);
@endphp

<div class="relative" style="width: {{200 + (count($displayImages) - 1) * 25}}px; height: {{200 + (count($displayImages) - 1) * 25}}px;">
    @foreach($displayImages as $index => $displayImage)
        <div class="absolute bg-gray-300 w-[200px] h-[200px] rounded-xl" style="bottom: {{$index * 25}}px; {{$side === 'sender' ? 'left' : 'right'}}: {{$index * 25}}px; z-index: {{5 - $index}}">
            <img
                src="{{ asset('storage/' . $displayImage) }}"
                class="object-cover cursor-pointer w-full h-full absolute rounded-xl"

                x-on:click="imagePreview = { openModal: true, images: {{ $images }}, index: {{$index}} }"
            />
        </div>
    @endforeach
</div>
