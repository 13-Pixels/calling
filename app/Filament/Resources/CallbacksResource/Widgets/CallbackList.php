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
        $currentDate = Carbon::today();
        $quoteToday = Callback::whereDate('created_at', $currentDate)->where('job_status','Quote')->count();
        $bookingToday = Callback::whereDate('created_at', $currentDate)->where('job_status','Booking')->count();
        return [
            Stat::make('Total Price', '123')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('New Quotes Today', $quoteToday)
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            Stat::make('New Bookings Today', $bookingToday)
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
