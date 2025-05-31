<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SoftDeletedUserTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    const int ITEMS_PER_PAGE = 10;

    public $searchTerm = '';

    public function restoreUser($userId)
    {
        $user = User::onlyTrashed()->where('id', $userId)->first();

        if ($user) {
            $user->status = 'alive';
            $user->restore();

            session()->flash('message', 'User has been restored successfully.');
            $this->dispatch('user-restored');
        } else {
            session()->flash('error', 'User not found in deleted records.');
        }
    }

    public function updatedSearchTerm() {
        $this->resetPage();
    }

    public function render()
    {
        // If searchTerm is empty, show all users
        $query = User::onlyTrashed();

        if (!empty($this->searchTerm)) {
            $query->where(function($q){
                $q->where('name', 'like', '%' .$this->searchTerm. '%')
                    ->orWhere('email', 'like', '%' .$this->searchTerm. '%')
                    ->orWhere('id', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('birth_date', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $users = $query->paginate(static::ITEMS_PER_PAGE);

        return view('livewire.admin.soft-deleted-user-table', [
            'users' => $users,
        ]);
    }
}
