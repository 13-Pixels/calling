<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Callback;
use Illuminate\Support\Carbon;

class CallbackStats extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;


    protected function getStats(): array
    {
        $quoteToday = Callback::whereDate('created_at', today())->where('job_status','pending_quote')->count();
        $bookingToday = Callback::whereDate('created_at', today())->where('job_status','booking')->count();
        $totalToday = Callback::sum('total');
        return [
            Stat::make('Today Total', $totalToday),
            Stat::make('Today Quotes', $quoteToday),
            Stat::make('Today Bookings', $bookingToday)
        ];    
    }
}
