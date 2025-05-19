<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);

        $user = auth()->user();

        // Save avatar into directed directory in the following directory structure.
        // public
        // |--- avatar

        $filePath = $request->file('avatar')->storeAs('avatar',
            $user->id . '_' . time() . '.' . $request->file('avatar')->getClientOriginalExtension(),  // Name the image.
            'public'  // Save to 'public'
        );

        // Create new avatar
        $avatar = $user->avatars()->create(['path' => $filePath, 'is_active' => false,]);

        return redirect()->back()->with('success', 'Avatar uploaded successfully.');
    }

    public function destroy($avatarId)
    {
        $user = auth()->user();
        $avatar = $user->avatars()->find($avatarId);

        if (!$avatar) {
            return redirect()->back()->with('error', 'Avatar not found.');
        }

        // Prevent deleting currently active avatar
        if ($avatar->is_active) {
            return redirect()->back()->with('error', 'Cannot delete active avatar.');
        }

        // Delete avatar from system and database.
        Storage::disk('public')->delete($avatar->path);
        $avatar->delete();

        return redirect()->back()->with('success', 'Avatar deleted successfully.');
    }

    public function setActive($avatarId)
    {
        $user = auth()->user();

        // Search the avatar that user want to use as active avatar.
        $avatar = $user->avatars()->find($avatarId);

        if (!$avatar) {
            return redirect()->back()->with('error', 'Avatar not found.');
        }

        // Update all avatar to non-active state.
        $user->avatars()->update(['is_active' => false]);

        // Mark chosen avatar as active.
        $avatar->is_active = true;
        $avatar->save();

        return redirect()->back()->with('success', 'Avatar set as active successfully.');
    }
}
