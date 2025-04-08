<div
    wire:snapshot="...your snapshot..." wire:effects="..."
    x-data="
    {
        height: 0,
        conversationElement:document.getElementById('conversation')
    }"

    x-init="
    height = conversationElement.scrollHeight;
    $nextTick(() => conversationElement.scrollTop=height);

    // Listen for user status update from Livewire event
    Echo.channel('user-status').listen('UserOnlineStatusUpdated', (event) => {
        console.log(event);
    });
    "

    @scroll-bottom.window="
    $nextTick(() => {
        conversationElement.style.overflowY = 'hidden';
        conversationElement.scrollTop = conversationElement.scrollHeight;
        conversationElement.style.overflowY = 'auto';
    });"
    class="flex h-screen overflow-hidden">

    <div class="grid grid-cols-6 overflow-hidden  w-full">
        <div class="col-span-4">
            <main class="w-full h-full grow border flex flex-col relative">
                <header class="flex items-center gap-2.5 p-2 border">
                    <a class="sm:hidden" wire:navigate href="{{ route('chat.index') }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class=" h-6 w-6 bi bi-chevron-left text-gray-500" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                      d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                            </svg>
                        </span>
                    </a>

                    <span>
                        @if ($receiver && $receiver->activeAvatar)
                            <img src="{{ asset('storage/' .$receiver->activeAvatar->path) }}" alt="Matched User Avatar"
                                 class="rounded-full h-10 w-10 ring ring-pink-500/40">
                        @else
                            <img src="https://randomuser.me/api/portraits/women/{{ rand(0, 99) }}.jpg" alt="Random User"
                                 class="rounded-full h-12 w-12 ring ring-pink-500/40">
                        @endif
                    </span>

                    <h5 class="font-bold text-gray-500 truncate">
                        {{ $receiver->name }}
                    </h5>
                    <div id="user-{{ $receiver->id }}"
                        class="flex items-center space-x-2 text-gray-500"

                        x-data="{
                            lastSeen: {{ $receiver->last_seen_at ? $receiver->last_seen_at->diffInSeconds(now()) : 999999 }},
                        }">
                       <span class="h-3 w-3 rounded-full"
                       :class="{
                        'bg-green-500': lastSeen < 120,
                        'bg-red-500': lastSeen >= 120
                    }"></span>
                       <span class="last-seen" x-ref="lastSeen">
                           {{ $receiver->last_seen_at ? 'Last seen: ' . $receiver->last_seen_at->diffForHumans() : 'Never' }}
                       </span>
                   </div>

                    <div class="ml-auto flex items-center gap-2  px-2">
                        <x-dropdown align="left">
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-three-dots text-gray-500 w-7 h-7" viewBox="0 0 16 16">
                                        <path
                                            d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content" class="w-48">
                                <button class="block w-full px-4 py-2 text-start text-sm leading-5 text-red-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Report
                                </button>
                            </x-slot>
                        </x-dropdown>

                        <a href="{{ route('dashboard') }}">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-x-octagon text-gray-500 w-7 h-7" viewBox="0 0 16 16">
                                    <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z" />
                                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                </svg>
                            </span>
                        </a>
                    </div>
                </header>

                <section x-data="{
                    messageDelete: {
                        openModal: false,
                        id: -1
                    },
                    imagePreview: {
                        openModal: false,
                        images: [],
                        index: 0,
                    },

                    previousImage() {
                        this.imagePreview.index = (this.imagePreview.index + 1) % this.imagePreview.images.length;
                    },

                    nextImage() {
                        this.imagePreview.index = (this.imagePreview.index - 1 + this.imagePreview.images.length) % this.imagePreview.images.length;
                    }}"
                    @scroll="
                    scrollTop = $el.scrollTop;

                    if (scrollTop <= 0){
                        @this.dispatch('loadMore');
                    }"

                    @update-height.window="
                    $nextTick(()=>{
                        newHeight = $el.scrollHeight;
                        oldHeight = height;

                        $el.scrollTop = newHeight - oldHeight;
                        height = newHeight;
                    })"
                    id="conversation"
                    class="flex flex-col gap-2 overflow-auto h-full p-2.5 overflow-y-scroll flex-grow overflow-x-hidden w-full my-auto" style="flex: 1 1 0;">

                    @foreach ($loadedMessages as $message)
                        {{-- Fuck Livewire --}}
                        <livewire:chat.message-bubble :$message :key="$message->id"/>
                    @endforeach

                    @if (count($loadedMessages) !== 0)
                        @php
                            $lastMessage = $loadedMessages[count($loadedMessages) - 1];
                        @endphp

                        <p @class(['text-xs text-gray-500', 'ml-auto' => $lastMessage->sender_id == auth()->id()])>Sent at {{ $lastMessage->created_at }}</p>
                    @endif

                    <div x-show="imagePreview.openModal" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-black bg-opacity-75 w-full h-full" x-on:click.self="imagePreview.openModal = false">
                            <div class="absolute inset-0 flex justify-center items-center">
                                <template x-for="(image, index) in imagePreview.images" :key="index">
                                    <img x-cloak x-show="index == imagePreview.index" class="max-w-full max-h-full object-contain" x-bind:src="'{{asset('storage')}}/' + image" alt="Preview Image"/>
                                </template>
                            </div>

                            {{-- Navigation buttons --}}
                            <div x-cloak x-show="imagePreview.images.length > 1">
                                <button type="button" class="absolute left-3 top-1/2 z-20 flex rounded-full -translate-y-1/2 bg-[#3F3F3FD0] hover:bg-[#212121D0]" x-on:click="previousImage()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="size-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M 19 10 l -6 6 l 6 6" stroke="white" stroke-width="3" fill="none"/>
                                    </svg>
                                </button>

                                {{-- Next --}}
                                <button type="button" class="absolute right-3 top-1/2 z-20 flex rounded-full -translate-y-1/2 bg-[#3F3F3FD0] hover:bg-[#212121D0]" x-on:click="nextImage()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="size-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M 13 10 l 6 6 l -6 6" stroke="white" stroke-width="3" fill="none"/>
                                    </svg>
                                </button>
                            </div>

                            <button class="absolute top-2 right-2 rounded-full size-8 bg-[#1A1A1AD0] p-2" x-on:click="imagePreview.openModal = false">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div x-show="messageDelete.openModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
                        <div class="bg-black bg-opacity-65 w-full h-full flex justify-center items-center" x-on:click.self="openDeleteModal = false">
                            <div class="bg-gray-700 p-8 rounded-xl">
                                <h1 class="text-white font-bold text-xl">Delete Message</h1>

                                <p class="text-white mt-4">Are you sure you want to delete this message? This action cannot be reverted on normal circumstance.</p>

                                <div class="flex justify-end gap-6 mt-4">
                                    <button class="bg-red-500 hover:bg-red-700 rounded-md px-6 py-2 text-white" @click="messageDelete.openModal = false" wire:click="delete(`${messageDelete.id}`)">Delete</button>
                                    <button class="bg-blue-300 hover:bg-blue-400 rounded-md px-6 py-2 text-black" @click="messageDelete.openModal = false">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <footer class="sticky bottom inset-x-0 p-1 border border-t-gray-400">
                    <livewire:chat.input-fields :conversation="$conversation" :receiver="$receiver" />
                </footer>
            </main>
        </div>

        <div class="col-span-2 border overflow-y-auto ">
            <livewire:chat.profile-card :user="$receiver" :conversation="$conversation" />
        </div>
    </div>
</div>
