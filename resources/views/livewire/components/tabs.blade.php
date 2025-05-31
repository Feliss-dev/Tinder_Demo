<section
    x-data="{ tab: {{request()->routeIs('chat') || request()->routeIs('chat') ? '"messages"' : '"matches"'}}}"
    @match-found.window="$wire.$refresh()"
    x-init="Echo.private('users.{{auth()->id()}}')
            .notification((notification) => {
                if (notification['type']=='App\\Notifications\\MessageSentNotification' ){
                    $wire.$refresh();
                }
            })"
    class="h-full overflow-x-hidden relative flex flex-col">

    <div class="flex flex-col h-full bg-white">
        {{-- Tab Buttons --}}
        <header class="flex flex-row max-w-full overflow-x-auto overflow-y-hidden whitespace-nowrap px-4 py-3 gap-5 items-center webkit-small-scrollbar flex-grow-0 flex-shrink-0 basis-14">
            <button @click="tab = 'matches'" :class="tab === 'matches' ? 'border-b-2 border-red-500' : ''" class="font-bold text-sm px-2 pb-1.5 flex-auto">
                Matches

                @php
                    $matchesCount = auth()->user()->matches()->count();
                @endphp

                @if ($matchesCount > 0)
                    <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                        {{$matchesCount}}
                    </span>
                @endif
            </button>

            <button @click="tab='messages'" :class="tab === 'messages' ? 'border-b-2 border-red-500' : ''" class="font-bold text-sm px-2 pb-1.5 flex-auto">
                Messages

                @php
                    $unreadMessageCount = auth()->user()->unReadMessagesCount()
                @endphp

                @if ($unreadMessageCount > 0)
                    <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                        {{$unreadMessageCount}}
                    </span>
                @endif
            </button>

            <button @click="tab='recommend'" :class="tab === 'recommend' ? 'border-b-2 border-red-500' : ''" class="font-bold text-sm px-2 pb-1.5 flex-auto">
                Recommends
            </button>
        </header>

        {{-- Tab Panel --}}
        <main class="flex-grow overflow-y-scroll webkit-small-scrollbar">
            <div x-show="tab == 'matches'" class="grid grid-cols-3 gap-2 p-2">
                @foreach ($matches as $i => $match)
                    @php
                        $partner = $match->swipe1->user_id == auth()->id() ? $match->swipe2->user : $match->swipe1->user;
                    @endphp

                    <div wire:click="createConversation('{{$match->id}}')" class="relative cursor-pointer">
                        <!-- Dot -->
                        <span class="absolute -top-1 -right-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10">
                                <circle r="5" cx="5" cy="5" fill="red"/>
                            </svg>
                        </span>

                        <x-avatar class="h-36 rounded-lg object-cover" :user="$partner" alt="Matched user"/>

                        <h5 class="absolute rounded-lg left-2 bottom-2 text-white bg-black/60 p-2 font-bold text-[10px]">
                            {{$partner->name}}
                        </h5>
                    </div>
                @endforeach
            </div>

            <div x-show="tab === 'messages'" x-cloak class="px-2">
                <ul>
                    @foreach ($conversations as $i => $conversation)
                        @php
                            $lastMessage = $conversation->messages()?->latest()->first();
                        @endphp

                        <li>
                            <a
                                wire:navigate
                                @class(['flex items-center gap-4 p-2 ', 'border-r-4 border-red-500 bg-white py-3'=>$selectedConversationId==$conversation->id])
                                href="{{route('chat', $conversation->id)}}">
                                <!-- make it change the sympathized when clicked -->

                                <div class="relative">
                                    <span class="inset-y-0 my-auto absolute -right-7">
                                        <svg
                                            @class([
                                                'w-14 h-14 stroke-[0.3px] stroke-white',
                                                'hidden'=> $i != 3,
                                                'text-red-500' => true
                                            ])

                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dot text-red-500 w-12 h-12" viewBox="0 0 16 16">
                                            <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                          </svg>
                                    </span>

                                    <span>
                                        <x-avatar class="rounded-full w-10 h-10 ring ring-pink-500/40" :user="$conversation->getReceiver()" alt="Matched User Avatar"/>
                                    </span>
                                </div>

                                <div class="overflow-hidden">
                                    <h6 class="font-bold truncate"> {{$conversation->getReceiver()->name}}</h6>
                                    <p
                                        @class([
                                            'text-gray-600 truncate truncate gap-2 flex items-center',
                                            'font-semibold text-black'=>!$lastMessage?->isRead() && $lastMessage?->sender_id != auth()->id(),
                                            'font-normal text-gray-600'=>$lastMessage?->isRead() && $lastMessage?->sender_id != auth()->id(),
                                            'font-normal text-gray-600'=>!$lastMessage?->isRead() && $lastMessage?->sender_id == auth()->id(),
                                        ])
                                    >
                                        @if ( $lastMessage?->sender_id != auth()->id())
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                  </svg>

                                            </span>
                                        @endif
                                        {{$conversation->messages()?->latest()->first()?->body}}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div x-show="tab === 'recommend'" x-cloak class="px-2 h-full flex flex-col">
                <livewire:recommendations />
            </div>
        </main>
    </div>
</section>
