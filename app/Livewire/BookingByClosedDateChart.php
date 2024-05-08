<?php

namespace App\Livewire;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BookingByClosedDateChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'bookingByClosedDateChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Booking By Closed Date';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data = Trend::query(Callback::where('job_status', 'booking')) 
            ->between(
               start: Carbon::parse($this->filterFormData['date_start']), 
                end: Carbon::parse($this->filterFormData['date_end']), 
            )
            ->perDay()
            ->count(); 

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BasicBarChart',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate), 
                ],
            ],
            'xaxis' => [
                'categories' => $data->map(fn (TrendValue $value) => $value->date), 

                'labels' => [
                    'name' => 'BlogPostsChart',
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'name' => 'BlogPostsChart',
                    
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }
    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->startOfYear()),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}
