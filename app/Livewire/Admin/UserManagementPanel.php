<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserManagementPanel extends Component {
    public array $datasets;
    public int $analyzingYear;
    public array $analyzingYearStats;

    public function mount() {
        $this->getChartData(Carbon::now()->year);
        $this->analyzingYear = Carbon::now()->year;
    }

    #[Computed]
    public function chart()
    {
        return Chartjs::build()
            ->name("UserRegistrationsChart")
            ->livewire()
            ->model('datasets')
            ->type("bar")
            ->size(["width" => 225, "height" => 100])
            ->optionsRaw("{
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Registration count'
                        },
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 'min-int-value',
                            suggestedMax: 'max-int-value'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'New User Registrations'
                    },
                    annotation: {
                        annotations: {
                            average: {
                                mode: 'horizontal',
                                type: 'line',
                                borderColor: 'black',
                                borderWidth: 3,
                                borderDash: [6, 6],
                                label: {
                                    display: true,
                                    content: (ctx) => {
                                        const values = ctx.chart.data.datasets[0].data;
                                        const average = values.reduce((a, b) => a + b, 0) / values.length;
                                        return 'Average: ' + average.toFixed(2);
                                    },
                                    position: 'end'
                                },
                                scaleID: 'y',
                                value: (ctx) => {
                                    const values = ctx.chart.data.datasets[0].data;
                                    return values.reduce((a, b) => a + b, 0) / values.length;
                                }
                            }
                        }
                    }
                }
            }");
    }

    public function render()
    {
        return view('livewire.admin.user-management-panel');
    }

    public function getChartData(int $year) {
        $response = Http::get(env('STATISTICS_URL') . 'join', [
            'year' => $year,
        ]);

        if ($response->ok()) {
            $this->analyzingYearStats['monthly_count'] = $response->json();
        } else {
            $this->analyzingYearStats['monthly_count'] = array_fill(0, 12, 0);
        }

        $this->analyzingYearStats['sum'] = array_reduce($this->analyzingYearStats['monthly_count'], function ($accumulator, $item) {
            return $accumulator + $item;
        }, 0);

        $this->analyzingYearStats['avg'] = $this->analyzingYearStats['sum'] / 12.0;

        $labels = array_map(function ($month) {
            return Carbon::create(null, $month)->format('F');
        }, range(1, 12));

        $this->datasets = [
            'datasets' => [
                [
                    "label" => "Registration",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $this->analyzingYearStats['monthly_count']
                ]
            ],
            'labels' => $labels
        ];
    }
}
