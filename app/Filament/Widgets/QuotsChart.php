<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class QuotsChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Quotes Per Month';
    protected static bool $isLazy = false;



    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Quotes',
                    'data' => [10],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
