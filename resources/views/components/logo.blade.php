@props(['width', 'height'])

<svg {{ $attributes }} width="{{$width}}" height="{{$height}}" viewbox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
    <path d="M 35 100 h 55 q 0 30, 35 35 v 40 l 30 -30 v 25 q 0 30, -30 30 h -5 v 30 l -30 -30 h -55 q -30 0, -30 -30 v -40 q 0 -30, 30 -30 Z" fill="#FF47FB" />
    <path d="M 128 26 h 90 q 30 0, 30 30 v 40 q 0 30, -30 30 h -55 l -30 30 v -30 h -5 q -30 0, -30 -30 v -40 q 0 -30, 30 -30 Z" fill="tomato" />
</svg>