@php
    use App\Models\User;
@endphp

<div class="w-full">
    @if ($replyingMessage != null)
        @php
            $who = $replyingMessage->sender_id == auth()->user()->id ? "yourself" : User::where('id', $replyingMessage->sender_id)->pluck('name')->first();
            $content = $replyingMessage->body;

            if ($content == null) {
                // Display as "<n attached images>"
                $amount = count(json_decode($replyingMessage->files));
                $content = "<" . $amount . " attached images>";
            }
        @endphp
        <div class="mb-2 relative">
            <p>Replying to <strong>{{ $who }}</strong>:</p>
            <p>{{$content}}</p>

            <button type="button" class="w-6 h-6 p-1 rounded-full absolute top-0 right-0 bg-white hover:bg-gray-200 active:bg-gray-300" wire:click="closeReplyingMessage">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                </svg>
            </button>
        </div>
    @endif

    <form x-data="{ body: @entangle('body') }" action="" @submit.prevent="$wire.sendMessage()" autocomplete="off"
          class="flex flex-row items-center gap-3 px-2">
        @csrf

        <label class="h-10 w-10 p-2 rounded-full flex-grow-0 flex-shrink-0 basis-auto flex justify-center items-center hover:bg-gray-200 active:bg-gray-300 hover:cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="black" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
            </svg>

            <input type="file" class="hidden" multiple>
        </label>

        <input x-model="body" type="text" autocomplete="off" autofocus
               placeholder="Write your message here" maxlength="1700"
               class="flex-auto bg-gray-100 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none placeholder-gray-500">

        <button type="submit" x-bind:disabled="!body?.trim()"
                class="w-10 h-10 p-2 rounded-full flex-grow-0 flex-shrink-0 basis-auto fill-black disabled:fill-gray-500 flex justify-center items-center disabled:bg-white hover:bg-gray-200 active:bg-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-full" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M15.854.146a.5.5 0 0 1 .11.54l-2.8 7a.5.5 0 1 1-.928-.372l1.895-4.738-7.494 7.494 1.376 2.162a.5.5 0 1 1-.844.537l-1.531-2.407L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM5.93 9.363l7.494-7.494L1.591 6.602z"/>
                <path fill-rule="evenodd"
                      d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.354a.5.5 0 0 0-.722.016l-1.149 1.25a.5.5 0 1 0 .737.676l.28-.305V14a.5.5 0 0 0 1 0v-1.793l.396.397a.5.5 0 0 0 .708-.708z"/>
            </svg>
        </button>
    </form>

    {{-- File Display --}}
    {{--    <form action="" x-data="{ body: @entangle('body') }" @submit.prevent="$wire.sendMessage()" autocomplete="off">--}}
    {{--        @csrf--}}

    {{--        --}}{{-- Replying Displayer --}}
    {{--        @if ($replyingMessage)--}}
    {{--            @php--}}
    {{--                $who = $replyingMessage->sender_id == auth()->user()->id ? "yourself" : \App\Models\User::find('id', $replyingMessage->sender_id)->pluck('name');--}}
    {{--                $content = $replyingMessage->body;--}}

    {{--                if ($content == null) {--}}
    {{--                    // Display as "<n attached images>"--}}
    {{--                    $amount = count(json_decode($replyingMessage->files));--}}
    {{--                    $content = "<" . $amount . " attached images>";--}}
    {{--                }--}}
    {{--            @endphp--}}
    {{--            <div>--}}
    {{--                <p>Replying to {{ $who }}: {{$content}}</p>--}}
    {{--            </div>--}}
    {{--        @endif--}}

    {{--        --}}{{-- Image displaying --}}
    {{--        <section x-data="{}">--}}

    {{--        </section>--}}
    {{--        <section x-data="{ files: @entangle('uploadingFiles'), }">--}}
    {{--            <div class="h-48" :class="{ 'hidden': files.length === 0 }">--}}
    {{--                <div class="flex flex-row max-w-full h-full overflow-x-auto overflow-y-hidden whitespace-nowrap p-1 gap-2">--}}
    {{--                    <template x-for="(item, index) in Object.values(files)" :key="index">--}}
    {{--                        <div class="relative h-full aspect-square border-2 border-gray-500 rounded-xl p-1 grid-flow-col" x-data="{ displayExtra: false }" @mouseleave="displayExtra = false;" @mouseover="displayExtra = true;" x-on:livewire-upload-progress="console.log($event.detail);>--}}
    {{--                            <div class="w-full h-full overflow-hidden flex flex-col">--}}
    {{--                                <div class="overflow-hidden" style="flex: 1 1 auto">--}}
    {{--                                    <img x-bind:src="item.url" x-bind:alt="item.name" class="h-full mx-auto object-cover" style="flex: 0 1 auto" />--}}
    {{--                                </div>--}}
    {{--                                <p style="flex: 0 1 1rem" x-text="item.name"></p>--}}
    {{--                            </div>--}}

    {{--                            <button type="button" class="bg-white border-2 border-gray-500 rounded-md absolute -top-1.5 -right-2.5 p-1"@click="$wire.deleteFile(index)">--}}
    {{--                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 16 16">--}}
    {{--                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>--}}
    {{--                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>--}}
    {{--                                </svg>--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </template>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </section>--}}

    {{--        --}}{{-- Hidden input --}}
    {{--        <input type="hidden" autocomplete="false" class="hidden">--}}
    {{--        <input type="file" id="file-upload" class="hidden" multiple @change="$wire.">--}}

    {{--        <div class="grid grid-cols-12 items-center">--}}
    {{--            --}}{{-- File Uploading --}}
    {{--            <button type="button" class="p-4" onclick="document.getElementById('file-upload').click();">--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16">--}}
    {{--                    <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>--}}
    {{--                </svg>--}}
    {{--            </button>--}}

    {{--            <input x-model="body" type="text" autocomplete="off" autofocus--}}
    {{--                   placeholder="Write your message here" maxlength="1700"--}}
    {{--                   class="col-span-9 bg-gray-100 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none placeholder-gray-500">--}}

    {{--            <button x-bind:disabled="!body?.trim() && !$wire.allFiles.length > 0" type="submit" class="p-4 disabled:fill-gray-500 fill-black">--}}
    {{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">--}}
    {{--                    <path fill-rule="evenodd" d="M15.854.146a.5.5 0 0 1 .11.54l-2.8 7a.5.5 0 1 1-.928-.372l1.895-4.738-7.494 7.494 1.376 2.162a.5.5 0 1 1-.844.537l-1.531-2.407L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM5.93 9.363l7.494-7.494L1.591 6.602z"/>--}}
    {{--                    <path fill-rule="evenodd" d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.354a.5.5 0 0 0-.722.016l-1.149 1.25a.5.5 0 1 0 .737.676l.28-.305V14a.5.5 0 0 0 1 0v-1.793l.396.397a.5.5 0 0 0 .708-.708z"/>--}}
    {{--                </svg>--}}
    {{--            </button>--}}
    {{--        </div>--}}
    {{--    </form>--}}
</div>
