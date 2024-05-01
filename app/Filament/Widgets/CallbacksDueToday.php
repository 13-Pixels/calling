<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Callback;
use Filament\Tables\Table;
use App\Enums\CallBackEnum;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\CallbacksResource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class CallbacksDueToday extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static bool $isLazy = false;
        protected static ?string $model = Callback::class;



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
    
    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Model $record): string => CallbacksResource::getUrl('edit',  ['record' => $record])),
        ];
    }
          
}
