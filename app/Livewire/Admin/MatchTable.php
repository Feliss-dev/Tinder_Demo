<?php

namespace App\Livewire\Admin;

use App\Models\SwipeMatch;
use Livewire\Component;
use Livewire\WithPagination;

class MatchTable extends Component
{
    use WithPagination;

    public $perPage = 10;

    public function render()
    {
        $query = SwipeMatch::query();

        $matches = $query->paginate($this->perPage);

        return view('livewire.admin.match-table', [
            'matches' => $matches,
        ]);
    }
}
