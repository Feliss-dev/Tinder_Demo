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
        // Validate trước khi cập nhật thông tin.
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'birth_date' => 'required|date',
            'images' => 'nullable|array', // Validate các ảnh là mảng
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Mỗi ảnh phải hợp lệ
            'languages' => 'nullable|array',
            'interests' => 'nullable|array',
            'genders' => 'nullable|array',
            'dating_goals' => 'nullable|array',
            'desired_genders' => 'nullable|array',
        ]);

        // Lấy thông tin người dùng hiện tại
        $user = User::find(Auth::user()->id);

        // Cập nhật thông tin người dùng (name, bio, birth_date)
        $user->name = $validatedData['name'];
        $user->bio = $validatedData['bio'];
        $user->birth_date = $validatedData['birth_date'];

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('user_images', 'public');
                $imagePaths[] = $path;
            }

            // Lưu các đường dẫn ảnh dưới dạng JSON
            $user->images = json_encode($imagePaths);
        }

        // Cập nhật ngôn ngữ người dùng chọn
        $user->languages()->sync($request->input('languages', []));

        // Cập nhật sở thích
        $user->interests()->sync($request->input('interests', []));

        // Cập nhật giới tính
        $user->genders()->sync($request->input('genders', []));

        // Cập nhật mục tiêu hẹn hò
        $user->datingGoals()->sync($request->input('datingGoals', []));

        // Cập nhật giới tính mong muốn
        $user->desiredGenders()->sync($request->input('desiredGenders', []));

        // Lưu lại thông tin người dùng
        $user->save();

        // Trả về thông báo thành công
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

}
