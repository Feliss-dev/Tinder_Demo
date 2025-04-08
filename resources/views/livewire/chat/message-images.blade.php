<div>
    @foreach (json_decode($message->files, true) as $index => $file)
        <div class="pt-1 {{$alignment}}">
            <img
                src="{{ asset('storage/' . $file) }}"
                alt="{{$file}}"
                class="object-cover cursor-pointer max-h-[220px] w-auto {{$alignment}}"

                x-on:click="imagePreview = { openModal: true, images: {{ $message->files }}, index: {{$index}} }"
            />

            <!-- Zooming Modal -->
{{--            <div x-show="showPreviewModal"--}}
{{--                 class="fixed inset-0 flex items-center justify-center z-50"--}}

{{--                 x-transition:enter="ease-out duration-300"--}}
{{--                 x-transition:enter-start="opacity-0 scale-90"--}}
{{--                 x-transition:enter-end="opacity-100 scale-100"--}}
{{--                 x-transition:leave="ease-in duration-200"--}}
{{--                 x-transition:leave-start="opacity-100 scale-100"--}}
{{--                 x-transition:leave-end="opacity-0 scale-90">--}}
{{--                <div class="bg-black bg-opacity-75 w-full h-full flex justify-center items-center"--}}
{{--                     x-on:click.self="showPreviewModal = false">--}}
{{--                    <img src="{{ asset('storage/' . $file) }}"--}}
{{--                         alt="Zoomed Image"--}}
{{--                         class="max-w-full max-h-full object-contain"--}}
{{--                    />--}}

{{--                    <button x-on:click="showPreviewModal = false"--}}
{{--                            class="absolute top-2 right-2 text-white text-2xl cursor-pointer bg-rose-600 bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-75">--}}
{{--                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">--}}
{{--                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    @endforeach
</div>
