<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;

class ReportManagementPanel extends Component
{
//    public int $analyzingYear;
//
//    public function mount() {
//        $this->analyzingYear = Carbon::now()->year;
//    }

    public function render()
    {
        return view('livewire.admin.report-management-panel');
    }
}
