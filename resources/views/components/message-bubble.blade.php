@props(['sender' => 'this'])

@php
$alignmentClasses = match ($sender) {
    'other' => 'mr-auto',
    default => 'ml-auto',
};

$colorClasses = match ($sender) {
    'other' => 'bg-gray-300',
    default => 'bg-blue-500'
};

$roundedClass = match ($sender) {
    'other' => 'rounded-bl-none',
    default => 'rounded-br-none'
}
@endphp

<div class="w-[85%] {{$alignmentClasses}}">
    {{-- Message bubble --}}
    <div class='rounded-2xl {{$colorClasses}} {{$roundedClass}}'>
        @if (!empty($message->body))
            <p @class(['p-2', 'text-white' => $sender == 'this', 'text-black' => $sender != 'this'])>{{$message->body}}</p>
        @endif
    </div>

    {{-- Files --}}
    @if ($message->files != null)
        @php
            $filenames = json_decode($message->files, true);
        @endphp

        @if (count($filenames) > 0)
            <img src="{{ asset('storage/' . $filenames[0]) }}" alt="" class="mt-1 max-w-[55%] {{$alignmentClasses}}">
        @endif
    @endif
</div>
