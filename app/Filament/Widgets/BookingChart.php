<?php

namespace App\Filament\Widgets;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class BookingChart extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Bookings Per Month';
    protected static bool $isLazy = false;



    protected function getData(): array
    {
        // Totals per month
        $quotes = Trend::query(Callback::where('job_status', 'booking'))
        ->between(
            start: now()->startOfYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Quotes',
                    'data' => $quotes->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $quotes->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
