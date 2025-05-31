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
        $this->user = User::withTrashed()->where('id', $userId)->firstOrFail();
        $this->queryReports($userId);
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

    public function warnUser() {
        if ($this->user != null) {
            $this->user->increment('warn_count');

            $this->inspectingReport->update([
                'resolved' => true,
            ]);
            $this->inspectingReportId = 0;
            $this->inspectingReport = null;
            $this->queryReports($this->user->id);

            session()->flash('success', 'User has been warned.');
            $this->dispatch('report-resolved');
        } else {
            session()->flash('error', 'User is null.');
        }
    }

    public function banUser() {
        if ($this->user != null) {
            $this->user->is_banned = true;

            $this->inspectingReport->update([
                'resolved' => true,
            ]);
            $this->inspectingReportId = 0;
            $this->inspectingReport = null;
            $this->queryReports($this->user->id);

            session()->flash('success', 'User has been banned.');
            $this->dispatch('report-resolved');
        } else {
            session()->flash('error', 'User is null.');
        }
    }

    public function ignoreReport() {
        $this->inspectingReport->update([
            'resolved' => true,
        ]);
        $this->inspectingReportId = 0;
        $this->inspectingReport = null;
        $this->queryReports($this->user->id);

        session()->flash('success', 'Report has been ignored.');
        $this->dispatch('report-resolved');
    }

    public function queryReports(int $userId) {
        $this->reportIds = MessageReport::where('resolved', false)->with("message")->whereHas("message", function ($query) use ($userId) {
            $query->where('sender_id', $userId);
        })->pluck('id')->sort();
    }

    public function render() {
        return view('livewire.admin.user-message-report-informations');
    }
}
