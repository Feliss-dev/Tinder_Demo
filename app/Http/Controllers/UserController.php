<?php

namespace App\Http\Controllers;

use App\Models\DatingGoal;
use App\Models\DesiredGender;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Gender;
use App\Models\Interest;
use App\Models\Language;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function admin_dashboard()
    {
        return view('layouts.admin.dashboard');
    }

    public function showProfileForm()
    {
        $languages = Language::all();
        $user = Auth::user();
        $genders = Gender::all();
        $interests = Interest::all();
        $datingGoals = DatingGoal::all();
        $desiredGenders = DesiredGender::all();
        return view('layouts.user.input', compact('user', 'languages', 'genders', 'interests', 'datingGoals', 'desiredGenders'));
    }

    public function userDashboard()
    {
        return view('layouts.app');
    }

    public function userProfile()
    {
        return view('profile');
    }

    public function viewMyDetails()
    {
        $user = Auth::user();
        return view('layouts.user.my_details', compact('user'));
    }

    public function updateInfor(Request $request)
    {
        // Validation.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'required|date',
            'images' => 'nullable|array', // Multiple images (array)
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image extensions.
            'languages' => 'nullable|array',
            'interests' => 'nullable|array',
            'genders' => 'nullable|array',
            'dating_goals' => 'nullable|array',
            'desired_genders' => 'nullable|array',
        ]);

        // Get user.
        $user = User::find(Auth::user()->id);

        // Update user info.
        $user->name = $validatedData['name'];
        $user->bio = $validatedData['bio'];
        $user->birth_date = $validatedData['birth_date'];

        // Processing images.
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('user_images', 'public');
                $imagePaths[] = $path;
            }

            // Save the image paths as json.
            $user->images = json_encode($imagePaths);
        }

        // Update and save informations.
        $user->languages()->sync($request->input('languages', []));
        $user->interests()->sync($request->input('interests', []));
        $user->genders()->sync($request->input('genders', []));
        $user->datingGoals()->sync($request->input('datingGoals', []));
        $user->desiredGenders()->sync($request->input('desiredGenders', []));
        $user->save();

        // Success.
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

    public function deleteImage(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $images = json_decode($user->images, true);

        $imageToDelete = $request->input('image');
        $updatedImages = array_filter($images, fn($img) => $img !== $imageToDelete);

        $user->images = json_encode(array_values($updatedImages)); // Re-index the array
        $user->save();

        if (Storage::disk('public')->exists($imageToDelete)) {
            Storage::disk('public')->delete($imageToDelete);
        }

        return response()->json(['message' => 'Image deleted successfully.']);
    }

}
