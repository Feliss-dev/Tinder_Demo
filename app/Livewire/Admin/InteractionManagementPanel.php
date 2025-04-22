<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Component;

class InteractionManagementPanel extends Component
{
    public array $datasets;
    public int $analyzingYear;

    public function mount() {
        $this->getChartData(Carbon::now()->year);
        $this->analyzingYear = Carbon::now()->year;
    }

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("InteractionChart")
            ->livewire()
            ->model('datasets')
            ->type("bar")
            ->size(["width" => 225, "height" => 100])
            ->options([
                'scales' => [
                    'x' => [
                        'title' => [
                            'display' => true,
                            'text' => 'Month'
                        ]
                    ],
                    'y' => [
                        'title' => [
                            'display' => true,
                            'text' => 'User count'
                        ],
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => false,
                        'text' => 'Monthly User Registrations'
                    ]
                ]
            ]);
    }

    public function render()
    {
        return view('livewire.admin.interaction-management-panel');
    }

    public function getChartData(int $year) {
//        $response = Http::get(env('STATISTICS_URL') . 'join', [
//            'year' => $year,
//        ]);
//
//        $dict = [];
//
//        if ($response->ok()) {
//            $dict = $response->json();
//        } else {
//            foreach (range(1, 12) as $month) {
//                $dict[$month] = 0;
//            }
//        }
//
//        $labels = array_map(function ($month) {
//            return Carbon::create(null, $month)->format('F');
//        }, array_keys($dict));
//
//        $data = array_values($dict);
//
//        $this->datasets = [
//            'datasets' => [
//                [
//                    "label" => "User Registration Count",
//                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
//                    "borderColor" => "rgba(38, 185, 154, 0.7)",
//                    "data" => $data
//                ]
//            ],
//            'labels' => $labels
//        ];
    }
}
