<?php

namespace App\Livewire;

use App\Models\Callback;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalChart extends ApexChartWidget
{
    protected int | string | array $columnSpan = 'full';

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
    protected static ?string $heading = 'Total Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $callback = new Callback;
        $quotes = $callback->count();
        $booking = $callback->where('callback_status', 'booked')->count();
        $lost = $callback->where('callback_status', 'lost')->count();
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
            'series' => [$quotes, $booking, $lost],
            'labels' => ['Quotes', 'Booking', 'Lost'],
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
