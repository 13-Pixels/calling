<?php

namespace App\Filament\Resources\CallbacksResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Callback;
use Illuminate\Support\Carbon;

class CallbackList extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $quoteToday = Callback::whereDate('created_at', today())->where('job_status','quote')->count();
        $bookingToday = Callback::whereDate('created_at', today())->where('job_status','booking')->count();
        return [
            Stat::make('Today Total', 0),
            Stat::make('Today Quotes', $quoteToday),
            Stat::make('Today Bookings', $bookingToday)
        ];
    }
}
