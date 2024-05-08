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
    protected static ?string $heading = 'Quotes By Day';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    // protected function getOptions(): array
    // {
    //     $data = Trend::model(Callback::class) 
    //         ->between(
    //            start: Carbon::parse($this->filterFormData['date_start']), 
    //             end: Carbon::parse($this->filterFormData['date_end']), 
    //         )
    //         ->perDay()
    //         ->count(); 
    //     return [
    //         'chart' => [
    //             'type' => 'line',
    //             'height' => 300,
    //         ],
    //         'series' => [
    //             [
    //                 'name' => 'QuotesByDayChart',
    //                 'data' => $data->map(fn (TrendValue $value) => $value->aggregate), 
    //             ],
    //         ],
    //         'xaxis' => [
    //             'labels' => [
    //                 'style' => [
    //                     'fontFamily' => 'inherit',
    //                 ],
    //             ],
    //         ],
    //         'yaxis' => [
    //             'labels' => [
    //                 'style' => [
    //                     'fontFamily' => 'inherit',
    //                 ],
    //             ],
    //         ],
    //         'colors' => ['#f59e0b'],
           
    //     ];
    // }
      protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BlogPostsChart',
                    'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
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
            'colors' => ['#f59e0b'],

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
