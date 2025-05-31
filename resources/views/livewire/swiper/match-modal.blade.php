<div x-cloak x-data="{ open: false }" @keydown.escape.window="open = false" @match-found.window="open = true">
    <template x-teleport="body">
        <div x-cloak x-show="open" class="fixed top-0 left-0 z-[99] flex items-center justify-center w-screen h-screen">
            <div x-trap.inert.noscroll="open" x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full py-6 bg-white h-full sm:h-[550px] px-7 sm:max-w-lg sm:rounded-lg border-2 border-rose-500">

                <!-- Close Button -->
                <div class=" items-center justify-between p-2 py-3 block ">
                    <button @click="open = false"
                            class="absolute top-0 right-0 flex items-center justify-center w-8 h-8 mt-5 mr-5 text-gray-600 rounded-full hover:text-gray-800 hover:bg-gray-50">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Main -->
                <main class="relative w-auto flex flex-col gap-y-9">
                    <div class="mx-auto flex flex-col gap-2 items-center justify-center">
                        <x-logo class="mx-auto" width="75" height="75"/>

                        <h5 class="font-bold text-3xl">It's a Match</h5>
                    </div>

                    <div class="flex items-center justify-center gap-4 mx-">
                        <span>
                            @if ($matchedUser != null)
                                <x-avatar class="rounded-full h-32 w-32 ring ring-pink-500/40" :user="$matchedUser" alt="Matched user"/>
                            @else
                                <div class="w-32 h-32 ring ring-pink-500/40 rounded-full"></div>
                            @endif
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="mx-auto flex flex-col gap-5">
                        <button @click="open = false" wire:click="createConversation"
                                class="bg-tinder text-white font-bold items-center px-3 py-2 rounded-full">
                            Send a message
                        </button>

                        <button @click="open=false"
                                class="bg-gray-500 text-white font-bold items-center px-3 py-2 rounded-full">
                            Continue Swiping
                        </button>
                    </div>
                </main>
            </div>
        </div>
    </template>
</div>
