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
    protected int | string | array $columnSpan = 'full';

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
       $count = Trend::query(Callback::where('callback_status', 'booked'))
        ->between(
            start: Carbon::parse($this->filterFormData['date_start']),
            end: Carbon::parse($this->filterFormData['date_end']),
        )
        ->perDay()
        ->count();
        $price = Trend::query(Callback::where('callback_status', 'booked'))
        ->between(
            start: Carbon::parse($this->filterFormData['date_start']),
                end: Carbon::parse($this->filterFormData['date_end']),
        )
        ->perDay()
        ->sum('total');
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Price',
                    'data' => $price->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'column',
                ],
                   [
                    'name' => 'Count',
                    'data' => $count->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'line',
                ],
            ],
            'stroke' => [
                'width' => [0, 4],
            ],
            'xaxis' => [
                'categories' => $count->map(fn (TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'type'=> "datetime",
            ],
            'yaxis' => [
                 ['title' =>[
                    'text'=> 'Price'
                ],
            ],
                ['opposite' => 'true',
                'title' =>[
                    'text'=> 'Count'
                ],
            ],
            ],
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }

     protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
            ->default(now()->subDays(30)->endOfDay()),
            DatePicker::make('date_end')->default(now()),
            
        ];
    }
    }