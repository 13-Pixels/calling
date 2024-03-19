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
        $currentDate = Carbon::today();
        $quote = Callback::where('job_status','Quote')->count();
        $booking = Callback::where('job_status','Booking')->count();
        return [
            Stat::make('Revenue', '123')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('New Quotes', $quote)
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            Stat::make('New Bookings', $booking)
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
