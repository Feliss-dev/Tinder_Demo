<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Models\SwipeMatch;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;

class Pdfdownload extends Component
{
    public $users;
    public $matches;
    public $images;

    public function mount(){
        $this->users = User::count();
        $this->matches = SwipeMatch::count();
        $this->images = count(Storage::files('public/user_images')) + count(Storage::files('public/avatars'));
    }

    public function downloadPDF(){
        $data = [
            'users' => $this->users,
            'matches' => $this->matches,
            'images' => $this->images,
        ];

        $pdf =  app('dompdf.wrapper')->loadView('livewire.admin.pdf_template', $data);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->stream();
        }, 'system_information.pdf');
    }

    public function render()
    {
        return view('livewire.admin.pdfdownload');
    }
}
