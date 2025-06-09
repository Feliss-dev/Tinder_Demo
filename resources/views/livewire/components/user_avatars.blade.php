<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new class extends Component {
    use WithFileUploads;

    public ?TemporaryUploadedFile $avatar = null;

    public function updatedAvatar()
    {
        abort_unless(auth()->check(), 401);

        $this->validate([
            'avatar' => 'image|mimes:jpeg,jpg,png|max:2048'
        ]);

        $user = auth()->user();

        $filePath = $this->avatar->storeAs('avatar',
            $user->id . '_' . time() . '.' . $this->avatar->getClientOriginalExtension(),  // Name the image.
            'public'  // Save to 'public'
        );

        $user->avatars()->create(['path' => $filePath, 'is_active' => false,]);
    }

    public function deleteAvatar($avatarId)
    {
        abort_unless(auth()->check(), 401);

        $user = auth()->user();
        $avatar = $user->avatars()->find($avatarId);

        if (!$avatar || $avatar->is_active) return;

        \Illuminate\Support\Facades\Storage::disk('public')->delete($avatar->path);
        $avatar->delete();
    }

    public function activeAvatar($avatarId) {
        $user = auth()->user();

        // Search the avatar that user want to use as active avatar.
        $avatar = $user->avatars()->find($avatarId);

        if (!$avatar) return;

        // Update all avatar to non-active state.
        $user->avatars()->update(['is_active' => false]);

        // Mark chosen avatar as active.
        $avatar->is_active = true;
        $avatar->save();

        $this->dispatch('user.avatar_changed');
    }
}; ?>

<div class="flex flex-row overflow-x-auto overflow-y-hidden whitespace-nowrap gap-2">
    <div
        class="h-[10rem] aspect-video flex-shrink-0 rounded-lg border-2 border-gray-300 flex items-center justify-center">

        <label class="bg-green-500 rounded-full text-white p-3 cursor-pointer">
            Add Avatar

            <input type="file" class="hidden" accept="image/png, image/gif, image/jpeg" wire:model="avatar">
        </label>
    </div>

    @foreach (auth()->user()->avatars()->orderByDesc('created_at')->get() as $avatar)
        <div class="relative h-[10rem] flex-shrink-0 rounded-lg border-2 @if($avatar->is_active) border-green-500 @else border-gray-300 @endif" x-data="{ hovered: false }" @mouseover="hovered = true" @mouseleave="hovered = false">
            <img class="object-cover h-full aspect-video" src="{{ asset('storage/' . $avatar->path) }}"
                 alt="Avatar"/>

            @if (!$avatar->is_active)
                <div x-cloak x-show="hovered" class="absolute inset-0 bg-black bg-opacity-30 flex flex-col items-center justify-center gap-2">
                    <button class="bg-green-500 text-white px-5 py-2 rounded-full hover:bg-green-600 focus:bg-green-700" wire:click="activeAvatar({{$avatar->id}})">
                        Active
                    </button>

                    <button class="bg-red-500 text-white px-5 py-2 rounded-full hover:bg-red-600 focus:bg-red-700" wire:click="deleteAvatar({{$avatar->id}})">
                        Delete
                    </button>
                </div>
            @endif
        </div>
    @endforeach
</div>
