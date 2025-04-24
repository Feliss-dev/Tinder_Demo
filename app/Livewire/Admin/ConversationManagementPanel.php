<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ConversationManagementPanel extends Component
{
    public array $datasets;
    public int $analyzingYear;
    public array $stats;

    public function mount() {
        $this->getChartData(Carbon::now()->year);
        $this->analyzingYear = Carbon::now()->year;
    }

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("ConversationCreateChart")
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
                            'text' => 'Conversation count'
                        ],
                        'ticks' => [
                            'stepSize' => 1,
                            'suggestedMin' => 'min-int-value',
                            'suggestedMax' => 'max-int-value'
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => false,
                        'text' => 'Conversation Created'
                    ]
                ]
            ]);
    }

    public function render()
    {
        return view('livewire.admin.conversation-management-panel');
    }

    public function getChartData(int $year) {
        $response = Http::get(env('STATISTICS_URL') . 'conversation', [
            'year' => $year,
        ]);

        $this->stats = [];

        if ($response->ok()) {
            $this->stats = $response->json();
        } else {
            $this->stats['sum'] = 0;

            foreach (range(1, 12) as $month) {
                $this->stats['months'][$month] = 0;
            }
        }

        $labels = array_map(function ($month) {
            return Carbon::create(null, $month)->format('F');
        }, array_keys($this->stats['months']));

        $data = array_values($this->stats['months']);

        $this->datasets = [
            'datasets' => [
                [
                    "label" => "Conversation Count",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data
                ]
            ],
            'labels' => $labels
        ];
    }
}
