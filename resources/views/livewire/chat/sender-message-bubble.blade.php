<div class="w-[85%] ml-auto" x-data="{ hover: false, openDropdown: false }" @mouseover="hover = true" @mouseleave="hover = false;">
    <div class="flex flex-row items-center justify-end">
        @if ($message->delete_status == 1)
            <div class='rounded-2xl w-fit border-dashed border-2 border-black ml-auto select-none'>
                <p class="p-2 text-black">Deleted!</p>
            </div>
        @else
            <div style="flex: 0 1 30px" class="flex justify-content-center align-items-center relative" x-on:keydown.escape.prevent.stop="openDropdown = false">
                <button x-cloak x-show="hover || openDropdown" x-on:click="openDropdown = !openDropdown" class="m-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black" viewBox="0 0 16 16">
                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
                    </svg>
                </button>

                <div x-cloak x-show="openDropdown" x-on:click.outside="openDropdown = false" class="absolute top-4 right-6 min-w-48 rounded-lg shadow-sm mt-2 z-10 bg-white p-1.5 outline-none border border-gray-200">
                    <button @click="openDropdown = false" wire:click="reply" class="p-2 w-full flex items-center rounded-md text-left text-gray-800 hover:bg-gray-200">
                        Reply
                    </button>

                    <hr/>

                    <button @click="messageDelete = { openModal: true, id: {{$message->id}} }; openDropdown = false;" class="p-2 w-full flex items-center rounded-md text-left text-red-500 hover:bg-gray-200">
                        Delete
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

                @if (!empty(json_decode($message->files)))
                    <livewire:chat.message-images :images="$message->files" :side="'sender'"/>
                @endif
            </div>
        @endif
    </div>
</div>
