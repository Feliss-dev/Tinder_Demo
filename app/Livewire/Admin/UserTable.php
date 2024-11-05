<?php

namespace App\Livewire\Admin;

use App\Events\NewNotification;
use DB;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

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
        // First, delete all swipes associated with the user
    FacadesDB::table('swipes')->where('user_id', $userId)->delete();

    // Then, delete the user
    $user = User::find($userId);
    if ($user) {
        $user->delete();
        session()->flash('message', 'User ' . $user->name . ' has been deleted.');
        $this->dispatch('userDeleted');
    } else {
        session()->flash('error', 'User not found.');
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

        return view('livewire.admin.user-table', [
            'users' => $users,
        ]);
    }
}
