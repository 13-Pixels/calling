<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Callback;
use App\Enums\CallBackEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;

class CallbacksDueToday extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;


    protected function getTableQuery(): Builder
    {
        return Callback::query()->latest();
    }
    
    protected function getTableColumns(): array 
    {
        return [
            Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('quote')->label('Quote #')->searchable(),
            Tables\Columns\TextColumn::make('enquiry_date')->label('Enquiry Date')->searchable()->dateTime('l jS F Y'),
            Tables\Columns\TextColumn::make('booking_date')->label('Booking Date')->searchable()->dateTime('l jS F Y'),
            Tables\Columns\TextColumn::make('callback_date')->label('Callback Date')->searchable()->dateTime('l jS F Y'),
            TextColumn::make('job_status')
            ->formatStateUsing(function (string $state) {
                return match ($state) {
                    'booking' => 'Booking',
                    'pending_quote' => 'Pending Quote',
                };
            })
            ->sortable()
            ->label('Job Status')
            ->searchable(),
            //Tables\Columns\TextColumn::make('location')->label('Location')->searchable(),
            TextColumn::make('callback_status')
            ->formatStateUsing(function (string $state) {
                return match ($state) {
                    'booked' => 'Booked',
                    'pending_quote' => 'Pending Quote',
                    'new' => 'New',
                    'lost' => 'Lost',
                };
            })
            ->label('Callback Status')
            ->searchable(),
            TextColumn::make('total')->prefix('₤'),

        ]; 
    }
}
