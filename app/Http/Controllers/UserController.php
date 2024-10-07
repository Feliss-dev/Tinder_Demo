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

        ]);

        // Update user informations
        $user = User::find(Auth::user()->id);
        // Cập nhật thông tin chung của người dùng (name, bio, birth_date)
        $user->update($validatedData);

        // Cập nhật các giá trị của radio (gender_id, desired_gender_id, dating_goal_id)
        $user->gender_id = $request->input('gender');  // gender_id từ radio "My Gender"
        $user->desired_gender_id = $request->input('desiredGender');  // desired_gender_id từ radio "Desired Gender"
        $user->dating_goal_id = $request->input('datingGoal');  // dating_goal_id từ radio "Dating Goal"

        // Lưu các thay đổi về giới tính, giới tính mong muốn, và mục tiêu hẹn hò
        $user->save();

        // Profile picture handling.
        $imagePaths = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('user_images', 'public');
                $imagePaths[] = $path;
            }

            $user->update(['images' => json_encode($imagePaths)]);
        }



        // Sync the languages the user selected
        $user->languages()->sync($request->languages ?? []);

        $user->interests()->sync($request->interests ?? []);



        // Save the updated user data


        // Report successfully profile updating.
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
}
