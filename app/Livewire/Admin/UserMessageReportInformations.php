<?php

namespace App\Livewire\Admin;

use App\Models\MessageReport;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class UserMessageReportInformations extends Component
{
    public bool $open = false;
    public ?User $user = null;
    public $reportIds = null;

    public int $inspectingReportId = 0;
    public ?MessageReport $inspectingReport = null;

    #[On('open-report-info-modal')]
    public function open(int $userId) {
        $this->user = User::where('id', $userId)->firstOrFail();
        $this->reportIds = MessageReport::with("message")->whereHas("message", function ($query) use ($userId) {
            $query->where('sender_id', $userId);
        })->pluck('id')->sort();
    }

    public function loadReport(int $id) {
        $this->inspectingReportId = $id;
        $this->inspectingReport = null;

        $this->js('$wire.loadReportInfo();');
    }

    public function loadReportInfo() {
        $this->inspectingReport = MessageReport::where('id', $this->inspectingReportId)->firstOrFail();
    }

    public function close() {
        $this->user = null;
        $this->reportIds = null;
        $this->inspectingReportId = 0;
        $this->inspectingReport = null;
    }

    public function render() {
        return view('livewire.admin.user-message-report-informations');
    }
}
