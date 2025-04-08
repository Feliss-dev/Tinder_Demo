<div class="w-[85%] ml-auto" x-data="{ hover: false, openDropdown: false, openDeleteModal: false }" @mouseover="hover = true" @mouseleave="hover = false;">
    <div class="flex flex-row items-center justify-end">
        @if ($message->delete_status == 1)
            <div class='rounded-2xl w-fit border-dashed border-2 border-black ml-auto'>
                <p class="p-2 text-black">Deleted!</p>
            </div>
        @else
            <div style="flex: 0 1 30px" class="flex justify-content-center align-items-center relative" x-on:keydown.escape.prevent.stop="openDropdown = false">
                <button x-cloak x-show="hover || openDropdown" x-on:click="openDropdown = !openDropdown" class="m-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16">
                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                    </svg>
                </button>

                <div x-show="openDropdown" x-on:click.outside="openDropdown = false" x-cloak class="absolute top-4 right-6 min-w-48 rounded-lg shadow-sm mt-2 z-10 bg-white p-1.5 outline-none border border-gray-200">
                    <button @click="openDeleteModal = true; openDropdown = false;" class="p-2 w-full flex items-center rounded-md text-left text-red-500 hover:bg-gray-200">
                        Delete
                    </button>

                    <button @click="openDropdown = false" wire:click="reply" class="p-2 w-full flex items-center rounded-md text-left text-gray-800 hover:bg-gray-200">
                        Reply
                    </button>
                </div>
            </div>

            <div style="flex: 0 9 auto" class="relative flex flex-col content-end">
                @if ($message->reply_id != null)
                    @php
                        $replyMessage = \App\Models\Message::where('id', $message->reply_id)->first();
                        $selfReply = $replyMessage->sender_id == auth()->id();
                    @endphp

                    <div class="relative w-fit ml-auto rounded-md {{$selfReply ? 'bg-blue-300' : 'bg-gray-200'}} p-2">
                        <p class="{{$selfReply ? 'text-white' : 'text-black'}} text-xs">{{$selfReply ? 'You' : \App\Models\User::where('id', $replyMessage->sender_id)->first()->name}} said:</p>
                        <p class="{{$selfReply ? 'text-white' : 'text-black'}}">{{$replyMessage->body}}</p>
                    </div>
                @endif

                <div class='rounded-2xl w-fit bg-blue-500 rounded-br-none ml-auto'>
                    @if (!empty($message->body))
                        <p class="text-white p-2">{{$message->body}}</p>
                    @endif
                </div>

                @if (!empty($message->files))
                    @php
                        $filenames = json_decode($message->files, true);
                    @endphp

                    @foreach ($filenames as $file)
                        <div class="pt-1 ml-auto" x-data="{ showPreviewModal: false }">
                            <img
                                src="{{ asset('storage/' . $file) }}"
                                alt="{{$file}}"
                                class="object-cover cursor-pointer max-h-[220px] w-auto ml-auto"

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
                                 x-transition:leave-end="opacity-0 scale-90">
                                <div class="bg-black bg-opacity-75 w-full h-full flex justify-center items-center"
                                     x-on:click.self="showPreviewModal = false">
                                    <img src="{{ asset('storage/' . $file) }}"
                                         alt="Zoomed Image"
                                         class="max-w-full max-h-full object-contain"
                                    />

                                    <button x-on:click="showPreviewModal = false"
                                            class="absolute top-2 right-2 text-white text-2xl cursor-pointer bg-rose-600 bg-opacity-50 p-2 rounded-full focus:outline-none hover:bg-opacity-75">
                                        &times;
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif
    </div>

    <div x-show="openDeleteModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
        <div class="bg-black bg-opacity-65 w-full h-full flex justify-center items-center" x-on:click.self="openDeleteModal = false">
            <div class="bg-gray-700 p-8 rounded-xl">
                <h1 class="text-white font-bold text-xl">Delete Message</h1>

                <p class="text-white mt-4">Are you sure you want to delete this message? This action cannot be reverted on normal circumstance.</p>

                <div class="flex justify-end gap-6 mt-4">
                    <button class="bg-red-500 hover:bg-red-700 rounded-md px-6 py-2 text-white" @click="openDeleteModal = false" wire:click="delete">Delete</button>
                    <button class="bg-blue-300 hover:bg-blue-400 rounded-md px-6 py-2 text-black" @click="openDeleteModal = false">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
