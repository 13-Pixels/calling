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

class CallbacksDue extends BaseWidget
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
            Tables\Columns\TextColumn::make('id')->searchable(),
            Tables\Columns\TextColumn::make('quote')->label('Quote')->searchable(),
            Tables\Columns\TextColumn::make('enquiry_date')->label('Enquiry Date')->searchable(),
            Tables\Columns\TextColumn::make('booking_date')->label('Booking Date')->searchable(),
            Tables\Columns\TextColumn::make('callback_date')->label('Callback Date')->searchable(),
            TextColumn::make('job_status')
            ->color(function (string $state) {
                return match ($state) {
                    CallBackEnum::BOOKING => 'success',
                    CallBackEnum::QUOTE => 'warning',
                };
            })
            ->formatStateUsing(fn (string $state): string => __("{$state}"))
            ->weight('bold')
            ->sortable()
            ->label('Job Status')
            ->searchable(),
            Tables\Columns\TextColumn::make('location')->label('Location')->searchable(),
            TextColumn::make('callback_status')
            ->color(function (string $state) {
                return match ($state) {
                    CallBackEnum::BOOKED => 'success',
                    CallBackEnum::PENDING => 'warning',
                    CallBackEnum::NEW => 'gray',
                    CallBackEnum::LOST => 'danger',
                };
            })
            ->formatStateUsing(fn (string $state): string => __("{$state}"))
            ->weight('bold')
            ->label('Callback Status')
            ->searchable(),
        ]; 
    }
}
