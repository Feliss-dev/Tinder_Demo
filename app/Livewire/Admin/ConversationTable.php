<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ConversationTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10; // Default items per page

    private $conversations;

    protected $listeners = [ 'conversation_table_initialize' => 'refreshConversationTable' ];

    public function mount() {
        $this->refreshConversationTable();
    }

    public function refreshConversationTable() {
        $query = Conversation::query();
        $this->conversations = $query->paginate($this->perPage);


    }

    public function render()
    {
        $this->refreshConversationTable();

        return view('livewire.admin.conversation-table', [
            'conversations' => $this->conversations,
        ]);
    }
}
