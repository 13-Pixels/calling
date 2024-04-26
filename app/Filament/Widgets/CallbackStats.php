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
        $quoteToday = Callback::whereDate('created_at', today())->where('job_status','Quote')->count();
        $bookingToday = Callback::whereDate('created_at', today())->where('job_status','Booking')->count();
        return [
            Stat::make('Today Total', '123'),
            Stat::make('Today Quotes', $quoteToday),
            Stat::make('New Bookings', $bookingToday)
        ];    
    }
}
