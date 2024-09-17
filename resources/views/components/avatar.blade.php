{{-- resources/views/components/avatar.blade.php --}}
@props(['src' => null, 'alt' => 'User Avatar'])

<div {{ $attributes->merge(['class' => "shrink-0 inline-flex items-center justify-center h-8 w-8 rounded-full overflow-hidden"]) }}>
    @if($src)
        <img
            @class(['shrink-0 w-full h-full object-cover object-center rounded-full'])
            src="{{ $src }}"
            alt="{{ $alt }}">
    @else
        <svg
            class="shrink-0 w-full h-full text-gray-300 bg-gray-100 dark:bg-gray-50 "
            fill="currentColor"
            viewBox="0 0 24 24"
            aria-label="Default Avatar">
            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
    @endif
</div>
