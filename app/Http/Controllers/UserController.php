<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function admin_dashboard(){
        return view('layouts.admin.dashboard');
    }
    public function showProfileForm(){
        return view('layouts.user.input');
    }
    public function userDashboard(){

        return view('layouts.app');
    }
    public function userProfile(){
        return view('profile');
    }
    public function updateInfor(Request $request){
       // Xác thực dữ liệu đầu vào
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'birth_date' => 'nullable|date',
        'gender' => 'nullable|string',
        'bio' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        // 'interests' => 'nullable|string',
        'desired_gender' => 'nullable|string',
        'dating_goal' => 'nullable|string',
    ]);

    // Cập nhật thông tin người dùng
    $user = User::find(Auth::user()->id);
    $user->update($validated);

    // Xử lý tải hình ảnh
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('user_images', 'public');
            $user->images()->create(['image_path' => $path]);
        }
    }

    // Xử lý sở thích và mục đích hẹn hò
    $preferencesData = $request->only(['interests', 'desired_gender', 'dating_goal']);
    $user->preferences()->updateOrCreate([], $preferencesData);

    return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
}
}
