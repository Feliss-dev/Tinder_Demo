<div x-data="{
        height: 0,
        conversationElement: document.getElementById('conversation'),
    }"

    x-init="
    height = conversationElement.scrollHeight;
    $nextTick(() => conversationElement.scrollTop=height);

    Echo.channel('user-status').listen('UserOnlineStatusUpdated', (event) => {
        console.log(event);
    });"

    @scroll-bottom.window="
    $nextTick(() => {
        conversationElement.style.overflowY = 'hidden';
        conversationElement.scrollTop = conversationElement.scrollHeight;
        conversationElement.style.overflowY = 'auto';
    });"
    class="flex overflow-hidden h-full">

    <div class="grid grid-cols-3 overflow-hidden w-full">
        <div class="col-span-2">
            <main class="w-full h-full grow border flex flex-col relative">
                <header class="flex items-center gap-2.5 p-2 border flex-initial">
                    <span>
                        <x-avatar class="rounded-full h-10 w-10 ring ring-pink-500/40" :user="$receiver" alt="Matched User Avatar" />
                    </span>

                    <h5 class="font-bold text-gray-500 truncate">
                        {{ $receiver->name }}
                    </h5>
                    <div id="user-{{ $receiver->id }}"
                        class="flex items-center space-x-2 text-gray-500"

                        x-data="{
                            lastSeen: {{ $receiver->last_seen_at ? $receiver->last_seen_at->diffInSeconds(now()) : 999999 }},
                        }">
                       <span class="h-3 w-3 rounded-full" :class="{ 'bg-green-500': lastSeen < 120, 'bg-red-500': lastSeen >= 120 }">

                       </span>
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
                                    {{__('Report')}}
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

                    messageReport: {
                        id: -1,
                        status: ''
                    },

                    imagePreview: {
                        openModal: false,
                        images: [],
                        index: 0,
                    },
                    imageValidation: {
                        openModal: false,
                        reason: '',
                    },

                    previousImage() {
                        this.imagePreview.index = (this.imagePreview.index + 1) % this.imagePreview.images.length;
                    },

                    nextImage() {
                        this.imagePreview.index = (this.imagePreview.index - 1 + this.imagePreview.images.length) % this.imagePreview.images.length;
                    }}"
                    @scroll="
                    if ($el.scrollTop <= 0) {
                        @this.dispatch('loadMore');
                    }"

                    @update-height.window="
                    $nextTick(()=>{
                        newHeight = $el.scrollHeight;
                        oldHeight = height;

                        $el.scrollTop = newHeight - oldHeight;
                        height = newHeight;
                    })"

                    @image-validation-forbidden.window="imageValidation = { openModal: true, reason: 'forbidden', }"

                    @image-validation-failed.window="
                    this.imageValidation = {
                        openModal: true,
                        reason: 'failed',
                    };"

                    @report-message-success.window="messageReport.status = 'success'"
                    @report-message-failed.window="messageReport.status = 'failed'"

                    id="conversation"
                    class="flex-auto flex flex-col overflow-auto h-full p-2.5 overflow-y-scroll flex-grow overflow-x-hidden w-full my-auto" style="flex: 1 1 0;">

                    <livewire:chat.conversation-container :$conversation :$receiver @class('flex-auto')/>

                    <div x-cloak x-show="imagePreview.openModal" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-black bg-opacity-75 w-full h-full" x-on:click.self="imagePreview.openModal = false">
                            <div class="absolute inset-0 flex justify-center items-center">
                                <template x-for="(image, index) in imagePreview.images" :key="index">
                                    <img x-cloak x-show="index == imagePreview.index" class="max-w-full max-h-full object-contain" x-bind:src="'{{asset('storage')}}/' + image" alt="Preview Image"/>
                                </template>
                            </div>

                             Navigation buttons
                            <div x-cloak x-show="imagePreview.images.length > 1">
                                <button type="button" class="absolute left-3 top-1/2 z-20 flex rounded-full -translate-y-1/2 bg-[#3F3F3FD0] hover:bg-[#212121D0]" x-on:click="previousImage()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="size-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M 19 10 l -6 6 l 6 6" stroke="white" stroke-width="3" fill="none"/>
                                    </svg>
                                </button>

                                 Next
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

                    <div x-cloak x-show="messageDelete.openModal" class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-black bg-opacity-65 w-full h-full flex justify-center items-center" x-on:click.self="messageDelete.openModal = false">
                            <div class="bg-white p-8 rounded-xl shadow-sm">
                                <h1 class="text-black font-bold text-xl">{{__('delete_message.title')}}</h1>

                                <p class="text-black mt-4">{{__('delete_message.body')}}</p>

                                <div class="flex justify-end gap-6 mt-4">
                                    <button class="bg-red-500 hover:bg-red-600 active:bg-red-700 rounded-md px-6 py-2 text-white" @click="messageDelete.openModal = false" wire:click="deleteMessage(`${messageDelete.id}`)">{{__('Delete')}}</button>
                                    <button class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 rounded-md px-6 py-2 text-white" @click="messageDelete.openModal = false">{{__('Cancel')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-cloak x-show="imageValidation.openModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65">
                        <section x-cloak x-show="imageValidation.reason === 'failed'" class="bg-gray-700 p-8 rounded-xl">
                            <h1 class="text-white font-bold text-xl">System Failure</h1>

                            <p class="text-white mt-4">Our system is having an issue while validating the images you posted. Please try again later.</p>

                            <div class="flex justify-end gap-6 mt-4">
                                <button class="bg-blue-300 hover:bg-blue-400 rounded-md px-6 py-2 text-black" @click="imageValidation.openModal = false">Ok</button>
                            </div>
                        </section>

                        <div x-cloak x-show="imageValidation.reason === 'forbidden'" class="bg-gray-700 p-8 rounded-xl">
                            <h1 class="text-white font-bold text-xl">Forbidden Content Detected</h1>

                            <p class="text-white mt-4">Our system detected that you are trying to post contents that are not allowed on our website.</p>
                            <p class="text-white mt-4">If you believe this is an error, please contact with moderators.</p>

                            <div class="flex justify-end gap-6 mt-4">
                                <button class="bg-blue-400 hover:bg-blue-500 active:bg-blue-600 rounded-md px-6 py-2 text-white" @click="imageValidation.openModal = false">Ok</button>
                            </div>
                        </div>
                    </div>

                    <div x-cloak x-show="messageReport.id > 0" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65">
                        <section class="bg-white p-8 rounded-xl" x-data="{ reasons: [], extra: '' }">
                            <h1 class="font-bold text-xl">{{__('report_message.title')}}</h1>

                            <p class="mt-4">{{__('report_message.body_text_1')}}</p>
                            <p>{{__('report_message.body_text_2')}}</p>

                            <form class="mt-3">
                                @csrf
                                @foreach(\App\Models\MessageReportReason::all() as $reason)
                                    <div class="flex items-center mb-4">
                                        <input id="checkbox-reason-{{$reason->id}}" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500" value="{{$reason->id}}" x-model="reasons">
                                        <label for="checkbox-reason-{{$reason->id}}" class="ms-2 text-black">{{__($reason->desc)}}</label>
                                    </div>
                                @endforeach

                                <p>{{__('report_message.extra')}}</p>
                                <textarea name="Extra" class="w-full h-20 resize-none" x-model="extra"></textarea>
                            </form>

                            <div class="flex justify-end gap-4 mt-4">
                                <button class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 disabled:opacity-50 rounded-md px-6 py-2 text-white" @click="messageReport.id = -1;" wire:click="reportMessage(`${messageReport.id}`, `${reasons}`, `${extra}`)" x-bind:disabled="reasons.length == 0" x-bind:class="{'cursor-not-allowed': reasons.length == 0}">{{__('Report')}}</button>
                                <button class="bg-red-500 hover:bg-red-600 active:bg-red-700 rounded-md px-6 py-2 text-white" @click="messageReport.id = -1">{{__('Cancel')}}</button>
                            </div>
                        </section>
                    </div>

                    <div x-cloak x-show="messageReport.status !== ''" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-65">
                        <section class="bg-gray-700 p-8 rounded-xl">
                            <div x-cloak x-show="messageReport.status === 'success'">
                                <h1 class="text-white font-bold text-xl">Message Report Successfully</h1>

                                <p class="text-white mt-4">Your report has been recorded, our moderator team will handle it sooner or later.</p>
                            </div>

                            <div x-cloak x-show="messageReport.status === 'failed'">
                                <h1 class="text-white font-bold text-xl">Message Report Failed</h1>

                                <p class="text-white mt-4">Your report has not been recorded due to system error, please try again later.</p>
                            </div>

                            <div class="flex justify-end gap-6 mt-4">
                                <button class="bg-blue-500 hover:bg-blue-600 active:bg-blue-700 rounded-md px-6 py-2 text-white" @click="messageReport.status = ''">Ok</button>
                            </div>
                        </section>
                    </div>
                </section>

                <footer class="sticky bottom p-1 border border-t-gray-400">
                    <livewire:chat.input-fields :conversation="$conversation" :receiver="$receiver" />
                </footer>
            </main>
        </div>

        <livewire:chat.profile-card :user="$receiver" :conversation="$conversation" />
    </div>
</div>
