<?php

namespace App\Filament\Pages;
use Filament\Pages\Page;
use App\Livewire\QuotesByDayChart;
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
        BookingByClosedDateChart::class
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
