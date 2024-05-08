<?php

namespace App\Livewire;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class QuotesByDayChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'quotesByDayChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Quotes By Day Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
         $quotes = Trend::query(Callback::where('callback_status','new')->orWhere('callback_status','pending_quote'))
        ->between(
            start: Carbon::parse($this->filterFormData['date_start']),
            end: Carbon::parse($this->filterFormData['date_end']),
        )
        ->perDay()
        ->count();
        $enquiry_date = Trend::query(Callback::where('enquiry_date',  '>', now()->subDays(30)->endOfDay()))
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
                    'name' => 'Quotes',
                    'data'=>  $quotes->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'column',
                ],
                [
                    'name' => 'Enqiry Date',
                      'data'=> $enquiry_date->map(fn (TrendValue $value) => $value->aggregate),
                    'type' => 'line',
                ],
            ],
            'stroke' => [
                'width' => [0, 4],
            ],
            'xaxis' => [
                'categories' =>  $enquiry_date->map(fn (TrendValue $value) => $value->aggregate),
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
            DatePicker::make('date_start')
            ->default(now()->subDays(30)->endOfDay()),
            DatePicker::make('date_end')->default(now()),
            
        ];
    }
}
