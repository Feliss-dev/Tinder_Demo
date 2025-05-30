<?php

namespace App\Livewire\Admin;

use App\Models\SwipeMatch;
use Livewire\Component;
use Livewire\WithPagination;

class MatchTable extends Component
{
    use WithPagination;

    const int ITEMS_PER_PAGE = 10;

    public function render()
    {
        $query = SwipeMatch::query();

        $matches = $query->paginate(static::ITEMS_PER_PAGE);

        return view('livewire.admin.match-table', [
            'matches' => $matches,
        ]);
    }
}
