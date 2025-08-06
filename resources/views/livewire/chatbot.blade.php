<div x-data="{ showChatbot: false, imageSource: null }" class="relative h-0">
    <button x-cloak x-show="!showChatbot" id="chatbot-toggler" @click="showChatbot = !showChatbot"
        class="fixed bottom-4 right-4 bg-pink-500 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-300 ">
        <!-- Hint Text -->
        <div  class="absolute -top-10 bg-pink-500 text-white text-xs font-medium px-2 py-1 rounded-lg shadow-md">
            Cupid AI
        </div>

        <!-- Icon States -->
        <span class="z-10">
            <img src="/chatbot.png" alt="Chatbot" className="w-5 h-5" />
        </span>
    </button>

    <div x-data="{ bot_type: @entangle('botType') }" x-cloak x-show="showChatbot" x-transition class="fixed bottom-4 right-4 bg-white shadow-lg rounded-lg w-96 h-[32rem] z-50 flex flex-col">
        {{-- Header --}}
        <header class="flex-initial bg-pink-500 p-4 flex justify-between items-center rounded-lg" x-data="{ showPanel: false }">
            <section class="relative">
                <button class="flex flex-row justify-start items-center" @click="showPanel = true;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="white" viewBox="0 0 16 16">
                        <path d="M6 12.796V3.204L11.481 8zm.659.753 5.48-4.796a1 1 0 0 0 0-1.506L6.66 2.451C6.011 1.885 5 2.345 5 3.204v9.592a1 1 0 0 0 1.659.753"/>
                    </svg>

{{--                    <span x-show="bot_type === 'auto'" class="text-white font-semibold text-lg align-middle">Cupid AI Chatbot</span>--}}
                    <span x-show="bot_type === 'conversation'" class="text-white font-semibold text-lg align-middle">Cupid AI Conversation Chatbot</span>
                    <span x-show="bot_type === 'image_gen'" class="text-white font-semibold text-lg align-middle">Cupid AI Image-Gen Chatbot</span>
                    <span x-show="bot_type === 'audio_gen'" class="text-white font-semibold text-lg align-middle">Cupid AI Audio-Gen Chatbot</span>
                </button>

                <div x-cloak x-show="showPanel" x-on:click.outside="showPanel = false" class="absolute left-0 origin-top-left z-20 bg-white outline-none border-gray shadow-sm flex flex-col rounded-lg">
{{--                    <button class="text-black text-start py-2 px-3 hover:bg-gray-200 active:bg-gray-300 rounded-tl-lg rounded-tr-lg" :class="bot_type == 'auto' ? 'bg-gray-300' : 'bg-white'" @click="bot_type = 'auto'; showPanel = false;">Auto</button>--}}
                    <button class="text-black text-start py-2 px-3 hover:bg-gray-200 active:bg-gray-300" :class="bot_type == 'conversation' ? 'bg-gray-300' : 'bg-white'" @click="bot_type = 'conversation'; showPanel = false;">Conversation</button>
                    <button class="text-black text-start py-2 px-3 hover:bg-gray-200 active:bg-gray-300" :class="bot_type == 'image_gen' ? 'bg-gray-300' : 'bg-white'" @click="bot_type = 'image_gen'; showPanel = false;">Image Generation</button>
                    <button class="text-black text-start py-2 px-3 hover:bg-gray-200 active:bg-gray-300 rounded-bl-lg rounded-br-lg" :class="bot_type == 'audio_gen' ? 'bg-gray-300' : 'bg-white'" @click="bot_type = 'audio_gen'; showPanel = false;">Audio Generation</button>
                </div>
            </section>

            <button @click="showChatbot = false" class="rounded-full p-2 text-xl size-8 bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="#ec4899" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
        </header>

        {{-- Messages --}}
        <section class="flex flex-col flex-auto p-4 overflow-y-auto gap-2"
             @scroll="
             if ($el.scrollTop <= 0) {
                $wire.loadMore();
             }"
             @update-chatbot-height.window="
                $nextTick(() => {
                    newHeight = $el.scrollHeight;
                    oldHeight = height;

                    $el.scrollTop = newHeight - oldHeight;
                    height = newHeight;
                });
            "
        >
            @foreach ($loadedMessages as $message)
                @if ($message->sender == 'user')
                    <div class="ml-auto max-w-[85%]">
                        <p class="px-4 py-2 rounded-lg shadow-md bg-pink-500 hover:bg-pink-600 text-white ml-auto w-fit">
                            {{ $message->message }}
                        </p>

                        @if ($message->images != null)
                            @foreach (json_decode($message->images) as $image)
                                <img src="{{asset('storage/' . $image)}}" alt="User Image" class="mt-2 size-48 max-w-48 max-h-48 aspect-square object-cover ml-auto cursor-pointer" @click="imageSource = $el.src"/>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="mr-auto max-w-[85%]">
                        @if (!empty($message->message))
                            <p class="px-4 py-2 rounded-lg shadow-md bg-gray-200 hover:bg-gray-300 text-black w-fit">
                                {{ $message->message }}
                            </p>
                        @endif

                        @if ($message->images != null)
                            @foreach (json_decode($message->images) as $image)
                                <img src="{{asset('storage/' . $image)}}" alt="Chatbot Image" class="mt-2 size-48 max-w-48 max-h-48 aspect-square object-cover cursor-pointer" @click="imageSource = $el.src"/>
                            @endforeach
                        @endif

                        @if ($message->bot_type == 'audio_gen')
                            @if ($message->audios != null)
                                @foreach (json_decode($message->audios) as $audio)
                                    <button class="flex-initial ml-2 size-7 p-1" @click="$refs.m{{$message->id}}_audio{{$loop->index}}.play()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="black" class="size-full" viewBox="0 0 16 16">
                                            <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/>
                                            <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/>
                                            <path d="M10.025 8a4.5 4.5 0 0 1-1.318 3.182L8 10.475A3.5 3.5 0 0 0 9.025 8c0-.966-.392-1.841-1.025-2.475l.707-.707A4.5 4.5 0 0 1 10.025 8M7 4a.5.5 0 0 0-.812-.39L3.825 5.5H1.5A.5.5 0 0 0 1 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 7 12zM4.312 6.39 6 5.04v5.92L4.312 9.61A.5.5 0 0 0 4 9.5H2v-3h2a.5.5 0 0 0 .312-.11"/>
                                        </svg>

                                        <audio src="{{asset('storage/' . $audio)}}" class="hidden" x-ref="m{{$message->id}}_audio{{$loop->index}}"></audio>
                                    </button>
                                @endforeach
                            @else
                                <div class="my-2 flex justify-start flex-row size-8">
                                    <svg aria-hidden="true" class="size-full text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>
                                </div>
                            @endif
                        @endif


{{--                        @if ($message->audios != null)--}}
{{--                            @if (!$message->audios['finish'])--}}
{{--                                <div class="my-2 flex justify-start flex-row size-8">--}}
{{--                                    <svg aria-hidden="true" class="size-full text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
{{--                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>--}}
{{--                                    </svg>--}}
{{--                                </div>--}}
{{--                            @else--}}
{{--                                <button class="flex-initial ml-2 size-7 p-1" @click="$refs.audio{{$loop->index}}.play()">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" fill="black" class="size-full" viewBox="0 0 16 16">--}}
{{--                                        <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/>--}}
{{--                                        <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/>--}}
{{--                                        <path d="M10.025 8a4.5 4.5 0 0 1-1.318 3.182L8 10.475A3.5 3.5 0 0 0 9.025 8c0-.966-.392-1.841-1.025-2.475l.707-.707A4.5 4.5 0 0 1 10.025 8M7 4a.5.5 0 0 0-.812-.39L3.825 5.5H1.5A.5.5 0 0 0 1 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 7 12zM4.312 6.39 6 5.04v5.92L4.312 9.61A.5.5 0 0 0 4 9.5H2v-3h2a.5.5 0 0 0 .312-.11"/>--}}
{{--                                    </svg>--}}

{{--                                    <audio src="{{$message->audio['source']}}" class="hidden" x-ref="audio{{$loop->index}}" autoplay></audio>--}}
{{--                                </button>--}}
{{--                            @endif--}}
{{--                        @endif--}}
                    </div>
                @endif
            @endforeach

            @switch ($state)
                @case(0)
                    @break

                @case(1)
                    <div class="my-2 flex justify-start flex-row size-8">
                        <svg aria-hidden="true" class="size-full text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                    </div>
                    @break

                @case(2)
                    <div class="my-2 flex justify-start max-w-xs px-4 py-2 rounded-lg shadow-md bg-gray-200">
                        <p wire:stream="generatingMessage"></p>
                    </div>
                    @break
            @endswitch
        </section>

        <div class="flex-grow-0 flex-shrink-0 basis-auto p-3 border-t-gray-300 border-t-2" x-data="{
                imagePreviewUrl: null,
                imageFile: null,

                setImage(event) {
                    const file = event.target.files[0];

                    this.imageFile = file;
                    this.imagePreviewUrl = URL.createObjectURL(file);
                },

                deleteImage() {
                    this.imagePreviewUrl = null;
                    this.imageFile = null;
                },

                uploadMessage() {
                    if (this.imageFile != null) {
                        $wire.upload('userImage', this.imageFile, (uploadedFilename) => {
                            $wire.sendMessage();
                        }, () => {}, (event) => {}, () => {});
                        this.deleteImage();
                    } else {
                        $wire.sendMessage();
                    }
                },
            }">

            <section x-cloak x-show="imageFile != null" class="mb-3">
                <div class="relative" x-data="{ displayExtra: false }" @mouseleave="displayExtra = false;" @mouseover="displayExtra = true;">
                    <div class="relative h-[3rem] gap-2 items-center pr-1 border-2 border-gray-500 rounded-tr-full rounded-br-full overflow-hidden flex flex-row flex-shrink-0">
                        <img x-bind:src="imagePreviewUrl" alt="Uploading Image" class="h-full aspect-square object-cover flex-initial"/>
                        <p x-text="imageFile?.name" class="max-w-56 truncate"></p>
                    </div>

                    <button type="button" x-show="displayExtra" class="bg-white hover:bg-gray-200 active:bg-gray-300 border-2 border-gray-500 rounded-full absolute top-0 right-0 p-[0.125rem]" @click="deleteImage">
                        <svg class="w-[0.7rem] h-[0.7rem]" xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    </button>
                </div>
            </section>

            <form @submit.prevent="uploadMessage()" class="chat-form flex items-center">
                <label class="mr-2 size-7 flex items-center justify-center cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="black" viewBox="0 0 16 16">
                        <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>
                    </svg>

                    <input type="file" class="hidden" @change="setImage" onclick="this.value = null;" accept="image/png, image/gif, image/jpeg">
                </label>

                <input type="text" wire:model="userMessage"
                    class="flex-grow border-none focus:outline-none focus:ring-2 rounded-lg px-4 py-2"
                    placeholder="Type a message..." required>

                <button type="submit" class="ml-2 size-7 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="black" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div x-cloak x-show="imageSource != null" class="fixed top-0 left-0 w-screen h-screen bg-[#212121D0] z-[200] overflow-auto flex flex-col justify-center items-center">
        <img x-bind:src="imageSource" alt="Image" class="w-full h-full inset-0 object-cover" />

        <button @click="imageSource = null" class="absolute top-2 right-2 rounded-full p-2 text-xl size-8 bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="black" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
            </svg>
        </button>
    </div>
</div>
