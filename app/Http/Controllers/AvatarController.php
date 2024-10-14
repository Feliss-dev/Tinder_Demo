<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $user = auth()->user();

    // Lưu ảnh vào folder đã chỉ định
    $filePath = $request->file('avatar')->storeAs(
        'avatar',  // Thư mục con 'avatar' trong 'public'
        time() . '.' . $request->file('avatar')->getClientOriginalExtension(),  // Đặt tên cho ảnh
        'public'  // Lưu vào disk 'public'
    );

    // Tạo avatar mới
    $avatar = $user->avatars()->create([
        'path' => $filePath,
        'is_active' => false,
    ]);

    return redirect()->back()->with('success', 'Avatar uploaded successfully.');
}

public function destroy($avatarId)
{
    $user = auth()->user();
    $avatar = $user->avatars()->find($avatarId);

    if (!$avatar) {
        return redirect()->back()->with('error', 'Avatar not found.');
    }

    // Kiểm tra xem avatar này có đang active không
    if ($avatar->is_active) {
        return redirect()->back()->with('error', 'Cannot delete active avatar.');
    }

    // Xóa file avatar khỏi hệ thống file
    Storage::disk('public')->delete($avatar->path);

    // Xóa avatar từ cơ sở dữ liệu
    $avatar->delete();

    return redirect()->back()->with('success', 'Avatar deleted successfully.');
}
public function setActive($avatarId)
{
    $user = auth()->user();

    // Tìm avatar mà người dùng muốn đặt làm active
    $avatar = $user->avatars()->find($avatarId);

    if (!$avatar) {
        return redirect()->back()->with('error', 'Avatar not found.');
    }

    // Cập nhật tất cả các avatar khác của người dùng thành không active
    $user->avatars()->update(['is_active' => false]);

    // Đặt avatar được chọn làm active
    $avatar->is_active = true;
    $avatar->save();

    return redirect()->back()->with('success', 'Avatar set as active successfully.');
}


}
