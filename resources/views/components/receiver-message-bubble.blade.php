<div wire:ignore class="max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2">
    <!-- Receiver Avatar -->
    <div class="shrink-0 mt-auto">
        <x-avatar class="w-7 h-7" src="https://picsum.photos/seed/' . rand() . '/300/300" />
    </div>

    <!-- Message -->
    <div class="flex flex-wrap text-[15px] border-gray-200/40 rounded-xl p-2.5 flex-col bg-[#f6f6f8fb]">
        <p class="whitespace-normal text-sm md:text-base tracking-wide lg:tracking-normal">
            {{$message->body}}
        </p>
    </div>
</div>
