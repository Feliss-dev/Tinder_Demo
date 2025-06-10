@php
    use App\Models\User;
@endphp

<div class="w-full"
     x-data="{
        uploadingImages: [],

        addImages(event) {
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                this.uploadingImages.push({
                    previewUrl: URL.createObjectURL(files[i]),
                    file: files[i],
                });
            }
        },

        deletePreviewImage(index) {
            this.uploadingImages.splice(index, 1)
        },

        uploadMessage() {
            if (this.uploadingImages.length == 0) {
                $wire.sendMessage();
            } else {
                $wire.uploadMultiple('images', this.uploadingImages.map(x => x.file), () => {
                    $wire.sendMessage();
                }, () => {
                    // Error
                }, (evt) => {
                    // Progress
                }, () => {
                    // Cancel
                });
            }

            this.uploadingImages.length = 0;
        },
     }">

    <section :class="{ 'hidden': uploadingImages.length === 0 }">
        <div class="flex flex-row max-w-full h-full overflow-x-auto overflow-y-hidden whitespace-nowrap p-1 gap-2">
            <template x-for="(item, index) in uploadingImages" :key="index">
                <div class="relative" x-data="{ displayExtra: false }" @mouseleave="displayExtra = false;" @mouseover="displayExtra = true;">
                    <div class="relative h-[3rem] gap-2 items-center pr-1 border-2 border-gray-500 rounded-tr-full rounded-br-full overflow-hidden flex flex-row flex-shrink-0">
                        <img x-bind:src="item.previewUrl" x-bind:alt="item.file.name" class="h-full aspect-square mx-auto object-cover flex-initial"/>
                        <p x-text="item.file.name" class="max-w-56 truncate"></p>
                    </div>

                    <button type="button" x-show="displayExtra" class="bg-white hover:bg-gray-200 active:bg-gray-300 border-2 border-gray-500 rounded-full absolute top-0 right-0 p-[0.125rem]" @click="deletePreviewImage(index)">
                        <svg class="w-[0.7rem] h-[0.7rem]" xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </section>

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

    <form x-data="{ body: @entangle('body') }" action="" @submit.prevent="uploadMessage()" autocomplete="off" class="flex flex-row items-center gap-3 px-2">
        @csrf

        <label class="h-10 w-10 p-2 rounded-full flex-grow-0 flex-shrink-0 basis-auto flex justify-center items-center hover:bg-gray-200 active:bg-gray-300 hover:cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-full" fill="black" viewBox="0 0 16 16">
                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z"/>
            </svg>

            <input type="file" class="hidden" multiple @change="addImages" onclick="this.value = null;" accept="image/png, image/gif, image/jpeg">
        </label>

        <input x-model="body" type="text" autocomplete="off" autofocus
               placeholder="Write your message here" maxlength="1700"
               class="flex-auto bg-gray-100 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none placeholder-gray-500">

        <button type="submit" x-bind:disabled="!body?.trim() && uploadingImages.length == 0"
                class="w-10 h-10 p-2 rounded-full flex-grow-0 flex-shrink-0 basis-auto fill-black disabled:fill-gray-500 flex justify-center items-center disabled:bg-white hover:bg-gray-200 active:bg-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-full" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                      d="M15.854.146a.5.5 0 0 1 .11.54l-2.8 7a.5.5 0 1 1-.928-.372l1.895-4.738-7.494 7.494 1.376 2.162a.5.5 0 1 1-.844.537l-1.531-2.407L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM5.93 9.363l7.494-7.494L1.591 6.602z"/>
                <path fill-rule="evenodd"
                      d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.354a.5.5 0 0 0-.722.016l-1.149 1.25a.5.5 0 1 0 .737.676l.28-.305V14a.5.5 0 0 0 1 0v-1.793l.396.397a.5.5 0 0 0 .708-.708z"/>
            </svg>
        </button>
    </form>
</div>
