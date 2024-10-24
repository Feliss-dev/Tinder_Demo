<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade;
use Illuminate\Http\Request;

class DownloadStatisticController extends Controller
{
    public function generatePdf() {
        $pdf = Facade\Pdf::loadView('pdf.admin_statistic', ['variable' => 69]);
        return $pdf->download('admin_statistic.pdf');
    }
}
