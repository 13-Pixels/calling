<?php

namespace App\Livewire;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class demo2 extends ApexChartWidget
{
    protected int | string | array $columnSpan = 'full';
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'demo2';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'demo2';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
          $count = Trend::query(Callback::where('callback_status', 'pending_quote')->orWhere('callback_status', 'new')->where('enquiry_date',  '>', now()->subDays(30)->endOfDay()))
        ->between(
            start: Carbon::parse($this->filterFormData['date_start']),
            end: Carbon::parse($this->filterFormData['date_end']),
        )
        ->perDay()
        ->count();
        $price = Trend::query(Callback::where('callback_status', 'pending_quote')->orWhere('callback_status', 'new')->where('enquiry_date',  '>', now()->subDays(30)->endOfDay()))
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
             'dataLabels' => [
                'enabled' => 'true',
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
                // 'labels' => [
                //     // 'style' => [
                //     //     'fontFamily' => 'inherit',
                //     // ],
                // ],
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

    protected function extraJsOptions(): ?RawJs
{
    return RawJs::make(<<<'JS'
    {
        // xaxis: {
        //     labels: {
        //         formatter: function (val, timestamp, opts) {
        //             return val + timestamp  ;
        //         }
        //     }
        // },
        yaxis: {
            labels: {
                formatter: function (val, index) {
                    return '£' + val
                }
            }
        },
        // tooltip: {
        //     x: {
        //         formatter: function (val) {
        //             return val + '/24'
        //         }
        //     }
        // },
        // dataLabels: {
        //     enabled: true,
        //     formatter: function (val, opt) {
        //         return opt.w.globals.labels[opt.dataPointIndex] + ': $' + val
        //     },
        //     dropShadow: {
        //         enabled: true
        //     },
        // }
    }
    JS);
}
}
