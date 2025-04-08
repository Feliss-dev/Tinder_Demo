<div id="conversation-container">
    @foreach ($loadedMessages as $message)
        {{-- Fuck Livewire --}}
        <livewire:chat.message-bubble :$message :key="$message->id"/>
    @endforeach

{{--    @if (count($loadedMessages) !== 0)--}}
{{--        @php--}}
{{--            $lastMessage = $loadedMessages[count($loadedMessages) - 1];--}}
{{--        @endphp--}}

{{--        <p @class(['text-xs text-gray-500', 'ml-auto' => $lastMessage->sender_id == auth()->id()])>Sent at {{ $lastMessage->created_at }}</p>--}}
{{--    @endif--}}

    @foreach($uploadingMessages as $uploadingMessage)
        <div class="flex flex-row items-center justify-end">
            <div style="flex: 0 0 30px" class="flex justify-content-center align-items-center relative loader w-[30px] h-[30px] mr-2"></div>

            <div style="flex: 0 1 auto" class="relative flex flex-col content-end">
                <div class='rounded-2xl w-fit bg-blue-300 rounded-br-none ml-auto'>
                    @if (!empty($uploadingMessage['body']))
                        <p class="text-white p-2">{{$uploadingMessage['body']}}</p>
                    @endif
                </div>

                @php
                    $uploadingImages = \Livewire\Features\SupportFileUploads\TemporaryUploadedFile::unserializeFromLivewireRequest($uploadingMessage['temporary_files']);
                @endphp

                @if (!empty($uploadingImages))
                    @php
                        $displayUploadingImages = array_slice($uploadingImages, 0, 4, true);
                    @endphp

                    <div class="relative" style="width: {{200 + (count($displayUploadingImages) - 1) * 25}}px; height: {{200 + (count($displayUploadingImages) - 1) * 25}}px;">
                        @foreach ($displayUploadingImages as $index => $displayImage)
                            <div class="absolute bg-gray-300 w-[200px] h-[200px] rounded-xl" style="bottom: {{$index * 25}}px; left: {{$index * 25}}px; z-index: {{5 - $index}}">
                                <img src="{{ $displayImage->temporaryUrl() }}" class="object-cover cursor-pointer w-full h-full absolute rounded-xl"/>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
