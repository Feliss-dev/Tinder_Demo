<div class="h-full ">
    <section
        x-data="{ tab: {{request()->routeIs('chat.index') || request()->routeIs('chat') ? '"messages"' : '"matches"'}}}"
        @match-found.window="$wire.$refresh()"
        x-init="Echo.private('users.{{auth()->id()}}')
                .notification((notification) => {
                    if (notification['type']=='App\\Notifications\\MessageSentNotification' ){
                        $wire.$refresh();
                    }
                })"
        class="mb-auto h-full overflow-x-hidden relative">

        <!-- Tab -->
        <div class="flex flex-col h-full">
            <div class="flex-1 flex flex-col">
                <!-- Tab Buttons -->
                <header class="flex-grow-0 flex-shrink-0 basis-0 flex items-center gap-5 mb-2 p-4 sticky top-0 bg-white z-10">
                    <button @click="tab = 'matches'" :class="tab === 'matches' ? 'border-b-2 border-red-500' : ''" class="font-bold text-sm px-2 pb-1.5" >
                        Matches

                        @if (auth()->user()->matches()->count() > 0)
                            <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                                {{auth()->user()->matches()->count()}}
                            </span>
                        @endif
                    </button>

                    <button @click="tab='messages'" :class="tab === 'messages' ? 'border-b-2 border-red-500' : ''" class="font-bold text-sm px-2 pb-1.5" >
                        Messages

                        @if (auth()->user()->unReadMessagesCount()> 0)
                            <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                                {{auth()->user()->unReadMessagesCount()}}
                            </span>
                        @endif
                    </button>
                </header>

                <!-- Tab Panel -->
                <div class="overflow-y-scroll" style="flex: 1 1 0;"> <!-- Must be 0, not 0% -->
                    <div x-show="tab == 'matches'" class="grid grid-cols-3 gap-2 p-2">
                        @foreach ($matches as $i=> $match)
                            <div wire:click="createConversation('{{$match->id}}')" class="relative cursor-pointer">
                                <!-- Dot -->
                                <span class="absolute -top-1 -right-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10">
                                        <circle r="5" cx="5" cy="5" fill="red"/>
                                    </svg>
                                </span>

                                <img src="https://randomuser.me/api/portraits/women/{{ rand(0, 99) }}.jpg" alt="image" class="h-36 rounded-lg object-cover">

                                <h5 class="absolute rounded-lg left-2 bottom-2 text-white bg-black/60 p-2 font-bold text-[10px]">
                                    {{$match->swipe1->user_id==auth()->id()?$match->swipe2->user->name:$match->swipe1->user->name}}
                                </h5>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-2" x-cloak x-show="tab === 'messages'">
                        <ul>
                            @foreach ($conversations as $i=> $conversation )
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

                                            <x-avatar class="w-14 h-14" src="https://picsum.photos/seed/' . rand() . '/300/300"/>
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
                </div>
            </div>
        </div>
    </section>
</div>
