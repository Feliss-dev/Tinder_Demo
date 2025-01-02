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

<div class="w-[85%] {{$alignmentClasses}}" x-data="{ hover: false, openDropdown: false }" @mouseover="hover = true" @mouseleave="hover = false;">
    <div class="flex flex-row items-center justify-end">
        {{-- Actions --}}
        <div style="flex: 0 1 30px" class="flex justify-content-center align-items-center relative" x-on:keydown.escape.prevent.stop="openDropdown = false">
            <button x-show="hover || openDropdown" x-on:click="openDropdown = !openDropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16" class="my-auto">
                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                </svg>
            </button>

            <!-- Panel -->
            <div x-show="openDropdown" x-on:click.outside="openDropdown = false" x-cloak class="absolute top-4 min-w-48 rounded-lg shadow-sm mt-2 z-10 bg-white p-1.5 outline-none border border-gray-200">
                <a href="" class="px-2 py-2 w-full flex items-center rounded-md text-left text-red-500 hover:bg-gray-200">
                    Delete
                </a>

                <a href="" class="px-2 py-2 w-full flex items-center rounded-md text-left text-gray-800 hover:bg-gray-200">
                    Reply
                </a>
            </div>
        </div>
{{--        <div style="flex: 0 1 30px" class="flex justify-content-center align-items-center relative" x-data="{--}}
{{--            openDropdown: false,--}}
{{--            toggle() {--}}
{{--                if (this.openDropdown) return this.close();--}}

{{--                this.openDropdown = true;--}}
{{--            },--}}
{{--            close(focusAfter) {--}}
{{--                if (!this.openDropdown) return;--}}

{{--                this.openDropdown = false;--}}
{{--                focusAfter && focusAfter.focus();--}}
{{--            }--}}
{{--        }" x-on:keydown.escape.prevent.stop="close($refs.button)">--}}
{{--            <button x-show="hover" x-on:click="toggle()">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16" class="my-auto">--}}
{{--                    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>--}}
{{--                </svg>--}}
{{--            </button>--}}

{{--            <div x-show="openDropdown" x-on:click.outside="close()" x-cloak class="absolute origin-top left-0 top-0 min-w-48 rounded-lg shadow-sm mt-2 z-10 bg-white p-1.5 outline-none border border-gray-200">--}}
{{--                <a href="" class="px-2 py-2 w-full flex items-center rounded-md text-left text-red-500 hover:bg-gray-200">--}}
{{--                    Delete--}}
{{--                </a>--}}

{{--                <a href="" class="px-2 py-2 w-full flex items-center rounded-md text-left text-gray-800 hover:bg-gray-200">--}}
{{--                    Reply--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div style="flex: 0 9 auto" class="flex flex-col content-end">
            {{-- Message bubble --}}
            <div class='rounded-2xl w-fit {{$colorClasses}} {{$roundedClass}} {{$alignmentClasses}}'>
                @if (!empty($message->body))
                    <p @class(['p-2', 'text-white' => $sender == 'this', 'text-black' => $sender != 'this'])>{{$message->body}}</p>
                @endif
            </div>

            @if (!empty($message->files))
                @php
                    $filenames = json_decode($message->files, true);
                @endphp

                @foreach ($filenames as $file)
                    <div class="pt-1 {{$alignmentClasses}}" x-data="{ showPreviewModal: false }">
                        <img
                            src="{{ asset('storage/' . $file) }}"
                            alt="Uploaded File"
                            class="object-cover cursor-pointer max-h-[220px] w-auto {{$alignmentClasses}}"

                            x-on:click="showPreviewModal = true"
                        />

                        <!-- Zooming Modal -->
                        <div x-show="showPreviewModal"
                             class="fixed inset-0 flex items-center justify-center z-50"

                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-90"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-90"
                        >
                            <div class="bg-black bg-opacity-75 w-full h-full flex justify-center items-center"
                             x-on:click.self="showPreviewModal = false">
                                <img src="{{ asset('storage/' . $file) }}"
                                     alt="Zoomed Image"
                                     class="max-w-full max-h-full object-contain"
                                />

                                <button
                                x-on:click="showPreviewModal = false"
                                class="absolute top-2 right-2 text-white text-2xl cursor-pointer bg-rose-600 bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-75"

                                >
                                &times;


                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
