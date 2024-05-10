<?php

namespace App\Filament\Pages;
use App\Livewire\demo;
use App\Livewire\demo2;
use Filament\Pages\Page;
use App\Livewire\TotalChart;
use App\Livewire\QuotesByDayChart;
use App\Livewire\LostByClosedDateChart;
use App\Livewire\BookingByClosedDateChart;

class Report_with_charts extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.report_1';

    protected static ?string $title = 'Reports With Charts';

    protected static ?string $navigationLabel = 'Reports With Charts';

    protected static ?string $slug = 'report-with-charts';

 
protected function getHeaderWidgets(): array
{
    return [
        QuotesByDayChart::class,
        BookingByClosedDateChart::class,
        LostByClosedDateChart::class,
        TotalChart::class,
        // demo2::class,
    ];
}

public function getWidgetData(): array
{
    return [
        'stats' => [
            'total' => 100,
        ],
    ];
}
}
