<?php

namespace App\Livewire;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'totalChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'TotalChart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
         $quotes = Trend::model(Callback::class)
        ->between(
            start: Carbon::parse($this->filterFormData['quotes_date_start']),
            end: Carbon::parse($this->filterFormData['quotes_date_end']),
        )
        ->perDay()
        ->count();
        $booking = Trend::query(Callback::where('callback_status','booked'))
        ->between(
            start: Carbon::parse($this->filterFormData['booking_date_start']),
                end: Carbon::parse($this->filterFormData['booking_date_end']),
        )
        ->perDay()
        ->count();
            $lost = Trend::query(Callback::where('callback_status','lost'))
        ->between(
            start: Carbon::parse($this->filterFormData['lost_date_start']),
                end: Carbon::parse($this->filterFormData['lost_date_end']),
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
                    'name' => 'Quotes',
                    'data'=>  $quotes->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'column',
                ],
                [
                    'name' => 'Booking',
                      'data'=> $booking->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'line',
                ],
                   [
                    'name' => 'Lost',
                      'data'=> $lost->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'column',
                ],
            ],
            'stroke' => [
                'width' => [0, 4],
            ],
            'xaxis' => [
                'categories' =>  $lost->map(fn (TrendValue $value) => $value->aggregate),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
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
            DatePicker::make('quotes_date_start')
            ->default(now()->subDays(30)->endOfDay()),
            DatePicker::make('quotes_date_end')->default(now()),
              DatePicker::make('booking_date_start')
            ->default(now()->subDays(30)->endOfDay()),
            DatePicker::make('booking_date_end')->default(now()),
              DatePicker::make('lost_date_start')
            ->default(now()->subDays(30)->endOfDay()),
            DatePicker::make('lost_date_end')->default(now()),
            
        ];
    }
}
