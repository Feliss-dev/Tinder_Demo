<?php

namespace App\Livewire\Admin;

use App\Events\NewNotification;
use DB;
use App\Models\User;
use App\Notifications\AdminMessageNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    const int ITEMS_PER_PAGE = 10;

    public $searchTerm = '';
    public $message;

    public function deleteUser($userId)
    {
        // Ensure we got a valid User before deleting.
        $user = User::find($userId);

        if ($user) {
            if ($user->status != 'alive') {
                $user->status = 'deleted';
                $user->delete();

                session()->flash('message', 'User ' . $user->name . ' has been soft deleted.');
                $this->dispatch('user-deleted');
            } else {
                session()->flash('error', 'User is no longer alive.');
            }
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    public function sendNotification($userId) {
        Log::debug("SendNotification to " . $userId);

        $user = User::find($userId);

        if (!$user) {
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
        $this->dispatch('notification-sent');
    }

    public function updatedSearchTerm() {
        $this->resetPage();
    }

    public function render()
    {
        // If searchTerm is empty, show all users
        $query = User::query();

        if (!empty($this->searchTerm)) {
            $query->where(function($q){
                $q->where('name', 'like', '%' .$this->searchTerm. '%')
                ->orWhere('email', 'like', '%' .$this->searchTerm. '%')
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('birth_date', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $users = $query->paginate(static::ITEMS_PER_PAGE);

        return view('livewire.admin.user-table', [
            'users' => $users,
        ]);
    }
}
