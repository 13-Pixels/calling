<?php

namespace App\Filament\Widgets;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class QuotsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Quotes Per Month';
    protected static bool $isLazy = false;



    protected function getData(): array
    {
        $quotes = Trend::query(Callback::where('job_status', 'pending_quote'))
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
