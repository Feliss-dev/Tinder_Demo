<?php

namespace App\Livewire\Admin;

use DB;
use App\Models\User;
use Illuminate\Support\Facades\DB as FacadesDB;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $perPage = 10; // Default items per page
    public $searchTerm = '';

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
    } else {
        session()->flash('error', 'User not found.');
    }
    }

    public function render()
    {
        // If searchTerm is empty, show all users
        $users = User::query();

        if (!empty($this->searchTerm)) {
            $users = $users->where('name', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('email', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                ->orWhere('birth_date', 'like', '%' . $this->searchTerm . '%');
        }

        $users = $users->paginate($this->perPage);

        return view('livewire.admin.user-table', [
            'users' => $users,
        ]);
    }
}
