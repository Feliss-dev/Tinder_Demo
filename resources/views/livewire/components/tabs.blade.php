@php
    use Illuminate\Support\Facades\Route;
@endphp

<section x-data="{ tab: '{{ Route::getCurrentRoute()->getName() === 'chat' ? 'messages' : 'matches' }}' }"
    @match-found.window="$wire.$refresh()"
    x-init="Echo.private('users.{{auth()->id()}}').notification((notification) => {
        if (notification['type']=='App\\Notifications\\MessageSentNotification' ){
            $wire.$refresh();
        }
    });"
    class="h-full overflow-x-hidden relative flex flex-col">

    <div class="flex flex-col h-full bg-white">
        {{-- Tab Buttons --}}
        <header class="flex flex-row max-w-full overflow-x-auto overflow-y-hidden whitespace-nowrap px-4 gap-5 items-center webkit-small-scrollbar flex-grow-0 flex-shrink-0 basis-auto">
            <button @click="tab = 'matches'" :class="tab === 'matches' ? 'border-b-2 border-red-500' : ''"
                    class="font-bold text-sm px-2 py-3 flex-auto">
                {{__('sidebar.tabs.matches')}}

                @if ($matches->count() > 0)
                    <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                        {{$matches->count()}}
                    </span>
                @endif
            </button>

            <button @click="tab = 'messages'" :class="tab === 'messages' ? 'border-b-2 border-red-500' : ''"
                    class="font-bold text-sm px-2 py-3 flex-auto">
                {{__('sidebar.tabs.messages')}}

                @php
                    $unreadMessageCount = auth()->user()->unReadMessagesCount()
                @endphp

                @if ($unreadMessageCount > 0)
                    <span class="rounded-full text-xs p-1 px-2 font-bold text-white bg-tinder">
                        {{$unreadMessageCount}}
                    </span>
                @endif
            </button>

            <button @click="tab = 'recommend'" :class="tab === 'recommend' ? 'border-b-2 border-red-500' : ''"
                    class="font-bold text-sm px-2 py-3 flex-auto">
                {{__('sidebar.tabs.recommends')}}
            </button>
        </header>

        {{-- Tab Panel --}}
        <main class="flex-grow overflow-y-scroll webkit-small-scrollbar flex-auto">
            <div x-show="tab === 'matches'" class="grid grid-cols-3 gap-2 p-2">
                @foreach ($matches as $match)
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
                @foreach ($conversations as $conversation)
                    @php
                        $lastMessage = $conversation->messages()?->latest()->first();
                    @endphp

                    <a
                        wire:navigate
                        @class(['flex items-center gap-4 p-2 ', 'border-r-4 border-red-500 bg-white py-3'=>$selectedConversationId==$conversation->id])
                        href="{{route('chat', $conversation->id)}}">

                        <div class="relative">
                            <span class="inset-y-0 my-auto absolute -right-7">
                                <svg class="w-14 h-14 stroke-[0.3px] stroke-white text-red-500"
                                    xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                                  </svg>
                            </span>

                            <span>
                                <x-avatar class="rounded-full w-10 h-10 ring ring-pink-500/40"
                                          :user="$conversation->getReceiver()" alt="Matched User Avatar"/>
                            </span>
                        </div>

                        <div class="overflow-hidden">
                            <h6 class="font-bold truncate"> {{$conversation->getReceiver()->name}}</h6>
                            <p
                                @class([
                                    'text-gray-600 truncate truncate gap-2 flex items-center',
                                    'font-semibold text-black' => !$lastMessage?->isRead() && $lastMessage?->sender_id != auth()->id(),
                                    'font-normal text-gray-600' => $lastMessage?->isRead() && $lastMessage?->sender_id != auth()->id(),
                                    'font-normal text-gray-600' => !$lastMessage?->isRead() && $lastMessage?->sender_id == auth()->id(),
                                ])
                            >
                                @if ( $lastMessage?->sender_id != auth()->id())
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.8" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/>
                                          </svg>
                                    </span>
                                @endif
                                {{$conversation->messages()?->latest()->first()?->body}}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div x-show="tab === 'recommend'" x-cloak class="px-2 h-full flex flex-col">
                <livewire:recommendations/>
            </div>
        </main>
    </div>
</section>
