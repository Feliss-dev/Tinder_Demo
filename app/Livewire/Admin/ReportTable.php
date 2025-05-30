<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use App\Models\MessageReport;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ReportTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    const int ITEMS_PER_PAGE = 10;

    public string $userSearchTerm;

    public function updatedUserSearchTerm() {
        $this->resetPage();
    }

    public function render()
    {
        $query = MessageReport::query()
            ->join('messages', 'messages.id', '=', 'message_reports.message_id')
            ->join('users', 'users.id', '=', 'messages.sender_id')
            ->groupBy('users.id')
            ->selectRaw("users.id, users.name, users.email, COUNT(message_reports.id) as count");

        if (!empty($this->userSearchTerm)) {
            $query->where('users.id', 'like', '%' . $this->userSearchTerm . '%')
                  ->orWhere('users.name', 'like', '%' . $this->userSearchTerm . '%')
                  ->orWhere('users.email', 'like', '%' . $this->userSearchTerm . '%');
        }

        return view('livewire.admin.report-table', [
            'users' => $query->paginate(static::ITEMS_PER_PAGE),
        ]);
    }
}
