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

<div class="max-w-[85%] {{$alignmentClasses}}">
    {{-- Message bubble --}}
    <div class='rounded-2xl w-fit {{$colorClasses}} {{$roundedClass}} {{$alignmentClasses}}'>
        @if (!empty($message->body))
            <p @class(['p-2', 'text-white' => $sender == 'this', 'text-black' => $sender != 'this'])>{{$message->body}}</p>
        @endif
    </div>


      {{-- File or Image Frame --}}
      @if (!empty($message->files))
      @php
          $filenames = json_decode($message->files, true);
      @endphp

      @foreach ($filenames as $file)
          <div class="border-2 border-gray-200 rounded-lg p-1 bg-gray-50 max-w-[35%] {{$alignmentClasses}}"
          x-data="{ showModal: false }">
              @if (str_starts_with(mime_content_type(storage_path('app/public/' . $file)), 'image/'))
                  <!-- Click vào ảnh để mở modal -->
                  <img
                      src="{{ asset('storage/' . $file) }}"
                      alt="Uploaded File"
                      class="w-full h-full object-cover cursor-pointer"

                      x-on:click="showModal = true"
                  />

                  <!-- Modal phóng to ảnh -->
                  <div
                      x-show="showModal"
                      class="fixed inset-0 flex items-center justify-center z-50"

                      x-transition:enter="ease-out duration-300"
                      x-transition:enter-start="opacity-0 scale-90"
                      x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="ease-in duration-200"
                      x-transition:leave-start="opacity-100 scale-100"
                      x-transition:leave-end="opacity-0 scale-90"

                      >
                      <div class="bg-black bg-opacity-75 w-full h-full flex justify-center items-center"
                      x-on:click.self="showModal = false">
                          <img
                              src="{{ asset('storage/' . $file) }}"
                              alt="Zoomed Image"
                              class="max-w-full max-h-full object-contain"
                          />
                          {{-- Button close --}}
                          <button
                          x-on:click="showModal = false"
                          class="absolute top-2 right-2 text-white text-2xl cursor-pointer bg-rose-600 bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-75"

                          >
                          &times;


                          </button>
                      </div>
                  </div>
              @else
                  <p class="text-sm text-gray-500 truncate">{{$file}}</p>
              @endif
          </div>
      @endforeach
  @endif

</div>
