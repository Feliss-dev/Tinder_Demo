<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SwipeManagementPanel extends Component
{
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
            ->name("SwipeChart")
            ->livewire()
            ->model('datasets')
            ->type("bar")
            ->size(["width" => 225, "height" => 100])
            ->optionsRaw("{
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        },
                        stacked: true,
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Swipe count'
                        },
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 'min-int-value',
                            suggestedMax: 'max-int-value'
                        },
                        stacked: true,
                    },
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Swipe'
                    },
                    tooltip: {
                        callbacks: {
                            footer: function(tooltipItems) {
                                return 'Total: ' + tooltipItems.reduce((acc, i) => acc + i.parsed.y, 0);
                            },
                        },
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
                                        const average = ctx.chart.data.datasets.reduce((acc, e) => acc + e.data.reduce((acc2, d) => acc2 + d, 0) / e.data.length, 0).toFixed(2);
                                        return 'Average: ' + average;
                                    },
                                    position: 'end'
                                },
                                scaleID: 'y',
                                value: (ctx) => {
                                    return ctx.chart.data.datasets.reduce((acc, e) => acc + e.data.reduce((acc2, d) => acc2 + d, 0) / e.data.length, 0).toFixed(2);
                                },
                            },
                        },
                    },
                },
            }");
    }

    public function render() {
        return view('livewire.admin.swipe-management-panel');
    }

    public function getChartData(int $year) {
        $response = Http::get(env('STATISTICS_URL') . 'swipe', [
            'year' => $year,
        ]);

        if ($response->ok()) {
            $this->analyzingYearStats['monthly_count'] = $response->json();
        } else {
            $this->analyzingYearStats['monthly_count']['left'] = array_fill(0, 12, 0);
            $this->analyzingYearStats['monthly_count']['right'] = array_fill(0, 12, 0);
            $this->analyzingYearStats['monthly_count']['up'] = array_fill(0, 12, 0);
        }

        foreach (['left', 'right', 'up'] as $key) {
            $this->analyzingYearStats['sum'][$key] = array_reduce($this->analyzingYearStats['monthly_count'][$key], function ($accumulator, $item) {
                return $accumulator + $item;
            }, 0);

            $this->analyzingYearStats['avg'][$key] = $this->analyzingYearStats['sum'][$key] / 12.0;
        }

        $labels = array_map(function ($month) {
            return Carbon::create(null, $month)->format('F');
        }, range(1, 12));

        $this->datasets = [
            'datasets' => [
                [
                    "label" => "Left Swipe",
                    "backgroundColor" => "rgba(255, 0, 0, 0.31)",
                    "borderColor" => "rgba(255, 0, 0, 0.7)",
                    "data" => $this->analyzingYearStats['monthly_count']['left'],
                ],
                [
                    "label" => "Up Swipe",
                    "backgroundColor" => "rgba(0, 157, 255, 0.31)",
                    "borderColor" => "rgba(0, 157, 255, 0.7)",
                    "data" => $this->analyzingYearStats['monthly_count']['up'],
                ],
                [
                    "label" => "Right Swipe",
                    "backgroundColor" => "rgba(0, 255, 0, 0.31)",
                    "borderColor" => "rgba(0, 255, 0, 0.7)",
                    "data" => $this->analyzingYearStats['monthly_count']['right'],
                ],
            ],
            'labels' => $labels
        ];
    }
}
