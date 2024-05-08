<?php

namespace App\Livewire;

use App\Models\Callback;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class QuotesByDayChart extends ChartWidget
{
    protected static ?string $heading = 'Quotes By Day';

    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        
        $quotes = Trend::model(Callback::class)
        ->between(
            start: now()->startOfYear(),
            end: now(),
        )
        ->perDay()
        ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Quotes ',
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
