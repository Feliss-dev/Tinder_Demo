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
    // Validate before update.
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'bio' => 'nullable|string',
        'birth_date' => 'required|date',
        'images' => 'nullable|array', // Validate images as an array
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Each image should be valid
        'languages' => 'nullable|array',
        'interests' => 'nullable|array',
        'genders' => 'nullable|array',
        'dating_goals' => 'nullable|array',
        'desired_genders' => 'nullable|array',
    ]);

    // Update user information
    $user = User::find(Auth::user()->id);

    // Cập nhật thông tin chung của người dùng (name, bio, birth_date)
    $user->name = $validatedData['name'];
    $user->bio = $validatedData['bio'];
    $user->birth_date = $validatedData['birth_date'];

    // Profile picture handling.
    if ($request->hasFile('images')) {
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('user_images', 'public');
            $imagePaths[] = $path;
        }

        // Save image paths as a JSON string in the 'images' field
        $user->images = json_encode($imagePaths);
    }

    // Sync the languages the user selected
    $user->languages()->sync($request->input('languages', []));

    // Sync interests
    $user->interests()->sync($request->input('interests', []));

    // Sync genders
    $user->genders()->sync($request->input('genders', []));

    // Sync dating goals
    $user->datingGoals()->sync($request->input('dating_goals', []));

    // Sync desired genders
    $user->desiredGenders()->sync($request->input('desired_genders', []));

    // Save the updated user data
    $user->save(); // Make sure to save the user after updating the fields

    // Report successfully profile updating.
    return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
}

}
