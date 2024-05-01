<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use App\Models\Callback;
use Filament\Tables\Table;
use App\Enums\CallBackEnum;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\CallbacksResource;
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
            Tables\Columns\TextColumn::make('quote')->label('Quote #')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('enquiry_date')->label('Enquiry Date')->searchable()->sortable()->dateTime('l jS F Y'),
            Tables\Columns\TextColumn::make('booking_date')->label('Booking Date')->searchable()->sortable()->dateTime('l jS F Y'),
            Tables\Columns\TextColumn::make('callback_date')->label('Callback Date')->searchable()->sortable()->dateTime('l jS F Y'),
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
            ->searchable()->sortable(),
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

     protected function getTableFilters(): array 
    {
        return [
             Filter::make('enquiry_date')
                    ->form([
                        DatePicker::make('enquiry_date')
                            ->placeholder(fn($state): string => now()->format('Y-m-d'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['enquiry_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('enquiry_date', $data['enquiry_date']),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['enquiry_date'] ?? null) {
                            $indicators['enquiry_date'] = 'Enquiry Date: ' . Carbon::parse($data['enquiry_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                    /////////Booking Date 
                    Filter::make('booking_date')
                    ->form([
                        DatePicker::make('booking_date')
                            ->placeholder(fn($state): string => now()->format('Y-m-d'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['booking_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('booking_date', $data['booking_date']),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['booking_date'] ?? null) {
                            $indicators['booking_date'] = 'Booking Date: ' . Carbon::parse($data['booking_date'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),

                     /////////Callback Date 
                     Filter::make('callback_date')
                     ->form([
                         DatePicker::make('callback_date')
                             ->placeholder(fn($state): string => now()->format('Y-m-d'))
                     ])
                     ->query(function (Builder $query, array $data): Builder {
                         return $query
                             ->when(
                                 $data['callback_date'],
                                 fn(Builder $query, $date): Builder => $query->whereDate('callback_date', $data['callback_date']),
                             );
                     })
                     ->indicateUsing(function (array $data): array {
                         $indicators = [];
                         if ($data['callback_date'] ?? null) {
                             $indicators['callback_date'] = 'Callback Date: ' . Carbon::parse($data['callback_date'])->toFormattedDateString();
                         }
 
                         return $indicators;
                     }),
                     
                     /////////Job Status 
                     SelectFilter::make('job_status')
                     ->options([
                        'pending_quote' => 'Pending Quote',
                        'booking' => 'Booking',
                     ])
                     ->query(function (Builder $query, array $data): Builder {
                         return $query
                             ->when(
                                 $status = $data['value'],
                                //  dd($status),
                                 fn(Builder $query, $data): Builder => $query->where('job_status', $status),
                             );
                     })
                     ->indicateUsing(function (array $data): array {
                         $indicators = [];
                         if ($data['value'] ?? null) {
                             $indicators['job_status'] = 'Job Status: ' . str_replace("_", " ", ucwords($data['value'], " /_"));
                         }
 
                         return $indicators;
                     }),
                      /////////Callback Status
                      SelectFilter::make('callback_status')
                     ->options([
                        'booked' => 'Booked',
                            'pending_quote' => 'Pending Quote',
                            'new' => 'New',
                            'lost' => 'Lost',
                     ])
                     ->query(function (Builder $query, array $data): Builder {
                         return $query
                             ->when(
                                 $status = $data['value'],
                                 fn(Builder $query, $data): Builder => $query->where('callback_status', $status),
                             );
                     })
                     ->indicateUsing(function (array $data): array {
                         $indicators = [];
                         if ($data['value'] ?? null) {
                             $indicators['callback_status'] = 'Callback Status: ' . str_replace("_", " ", ucwords($data['value'], " /_"));
                         }
                         return $indicators;
                     }),
        ];
    } 
          
}
