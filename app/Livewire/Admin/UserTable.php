<?php

namespace App\Livewire\Admin;

use App\Events\NewNotification;
use DB;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10; // Default items per page
    public $searchTerm = '';
    public $message;

    protected $listeners = [
        'searchUsers' => 'updateSearchTerm'
    ];

    public function updateSearchTerm($searchTerm)
    {
        $this->searchTerm = $searchTerm;
        $this->resetPage(); // Reset pagination to the first page when new search term is applied
    }

    public function deleteUser($userId)
    {
         // Tìm người dùng
    $user = User::find($userId);
    if ($user) {
        // Lưu thông tin người dùng vào bảng deleted_users
        FacadesDB::table('deleted_users')->insert([
            'name' => $user->name,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'birth_date' => $user->birth_date,
            'bio' => $user->bio,
            'images' => $user->images,
            'is_fake' => $user->is_fake,
            'email_verified_at' => $user->email_verified_at,
            'password' => $user->password,
            'google_id' => $user->google_id,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'deleted_at' => now(), // Lưu thời gian xóa
        ]);

        // Xóa tất cả các swipes liên quan đến người dùng
        FacadesDB::table('swipes')->where('user_id', $userId)->delete();

        // Xóa người dùng
        $user->delete();

        session()->flash('message', 'User ' . $user->name . ' has been deleted and stored temporarily.');
        $this->dispatch('userDeleted');
    } else {
        session()->flash('error', 'User not found.');
    }
    }

    public function restoreUser($userId)
{
    // Tìm người dùng trong bảng deleted_users
    $deletedUser = FacadesDB::table('deleted_users')->where('id', $userId)->first();

    if ($deletedUser) {
        // Khôi phục người dùng trở lại bảng users
        User::create([
            'name' => $deletedUser->name,
            'email' => $deletedUser->email,
            'is_admin' => $deletedUser->is_admin,
            'birth_date' => $deletedUser->birth_date,
            'bio' => $deletedUser->bio,
            'images' => $deletedUser->images,
            'is_fake' => $deletedUser->is_fake,
            'email_verified_at' => $deletedUser->email_verified_at,
            'password' => $deletedUser->password,
            'google_id' => $deletedUser->google_id,
            'created_at' => $deletedUser->created_at,
            'updated_at' => now(),
        ]);

        // Xóa bản ghi từ bảng deleted_users
        FacadesDB::table('deleted_users')->where('id', $userId)->delete();

        session()->flash('message', 'User has been restored successfully.');
        $this->dispatch('userRestored');
    } else {
        session()->flash('error', 'User not found in deleted records.');
    }
}


    public function sendNotification($userId){

        $this->dispatch('notification-sent');
        $user = User::find($userId);

        if(!$user){
            session()->flash('error', 'User not found.');
            return;
        }

       // Send notification to user

        // Gửi thông báo đến người dùng
        FacadesNotification::send($user, new AdminMessageNotification($this->message, $userId));

        // Phát trực tiếp thông báo qua Laravel Echo
        broadcast(new NewNotification($this->message, $userId))->toOthers();

        // NewNotification::broadcast($this->message, $userId)->toOthers();

        session()->flash('success', 'Notification sent successfully!');

    }

    public function render()
    {
        // If searchTerm is empty, show all users
       $query = User::query();

       if(!empty($this->searchTerm)) {
           $query->where(function($q){
            $q->where('name', 'like', '%' .$this->searchTerm. '%')
            ->orWhere('email', 'like', '%' .$this->searchTerm. '%')
            ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
            ->orWhere('birth_date', 'like', '%' . $this->searchTerm . '%');
           });
       }

        $users = $query->paginate($this->perPage);


    // Retrieve deleted users from deleted_users table
        $deletedUsers = FacadesDB::table('deleted_users')->get();

        return view('livewire.admin.user-table', [
            'users' => $users,
            'deletedUsers' => $deletedUsers,
        ]);
    }
}
