<?php

namespace App\Livewire\Admin;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\SwipeMatch;
use App\Models\User;
use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminTabPanel extends Component
{
    public function downloadUsersPDF() {
        $pdf = Pdf::loadView('livewire.admin.user_management_pdf', [
            'user_count' => User::count(),
            'deleted_user_count' => DB::table('deleted_users')->count(),
            'image_count' => count(Storage::files('public/user_images')) + count(Storage::files('public/avatars')),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'user_informations.pdf');
    }

    public function downloadConversationsPDF() {
        $pdf = Pdf::loadView('livewire.admin.conversation_management_pdf', [
            'conversation_count' => Conversation::count(),
            'message_count' => Message::count(),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'conversation_informations.pdf');
    }

    public function render()
    {
        return view('livewire.admin.admin-tab-panel');
    }
}
