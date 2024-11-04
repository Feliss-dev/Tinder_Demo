<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\WithPagination;

class ConversationTable extends Component
{
    use WithPagination;

    public $perPage = 10; // Default items per page

    public function render()
    {
        $query = Conversation::query();

        $conversations = $query->paginate($this->perPage);

        return view('livewire.admin.conversation-table', [
            'conversations' => $conversations,
        ]);
    }
}
