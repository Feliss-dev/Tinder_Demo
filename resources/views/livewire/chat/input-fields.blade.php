<div class="w-full h-full">
    {{-- File Display --}}
    <form action="" x-data="{ body: @entangle('body') }" @submit.prevent="$wire.sendMessage()" autocomplete="off">
        @csrf

        <livewire:chat.file-viewer />

        {{-- Hiding input --}}
        <input type="hidden" autocomplete="false" style="display: none">
        <input type="file" id="file-upload" class="hidden" wire:model="files" multiple>

        <div class="grid grid-cols-12 items-center">
            {{-- File Uploading --}}
            <button type="button" class="p-4" onclick="document.getElementById('file-upload').click();">
                <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16">
                    <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>
                </svg>
            </button>

            <input x-model="body" type="text" autocomplete="off" autofocus
                   placeholder="Write your message here" maxlength="1700"
                   class="col-span-9 bg-gray-100 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none placeholder-gray-500">

            <button x-bind:disabled="!body?.trim() && !$wire.allFiles.length > 0" type="submit" class="p-4 disabled:fill-gray-500 fill-black">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15.854.146a.5.5 0 0 1 .11.54l-2.8 7a.5.5 0 1 1-.928-.372l1.895-4.738-7.494 7.494 1.376 2.162a.5.5 0 1 1-.844.537l-1.531-2.407L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM5.93 9.363l7.494-7.494L1.591 6.602z"/>
                    <path fill-rule="evenodd" d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.354a.5.5 0 0 0-.722.016l-1.149 1.25a.5.5 0 1 0 .737.676l.28-.305V14a.5.5 0 0 0 1 0v-1.793l.396.397a.5.5 0 0 0 .708-.708z"/>
                </svg>
            </button>
        </div>
    </form>
</div>
