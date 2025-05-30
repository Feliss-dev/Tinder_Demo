<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ConversationTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    const int ITEMS_PER_PAGE = 10;

    public $searchTerm = '';

    public function render()
    {
        $query = Conversation::query();

        if (!empty($this->searchTerm)) {
            $query->whereRelation('sender', 'name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereRelation('receiver', 'name', 'like', '%' . $this->searchTerm . '%');
        }

        $conversations = $query->paginate(static::ITEMS_PER_PAGE);

        return view('livewire.admin.conversation-table', [
            'conversations' => $conversations,
        ]);
    }
}
