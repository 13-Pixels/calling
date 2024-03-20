<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallbacksResource\Pages;
use App\Filament\Resources\CallbacksResource\RelationManagers;
use App\Models\Callback;
use App\Enums\CallBackEnum;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Datepicker;
use Filament\Forms\Components\Select;
use App\Models\Customer;

class CallbacksResource extends Resource
{
    protected static ?string $model = Callback::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        $customers = Customer::pluck('name', 'id');
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        TextInput::make('quote')->label('Quote #')->required(),
                        Select::make('customer_id')->label('Customer')->options($customers)->required(),
                        Datepicker::make('enquiry_date')->label('Enquiry Date')->required(),
                        Datepicker::make('booking_date')->label('Booking Date')->required(),
                        ])->columns(2),


                        Section::make('')
                        ->schema([
                            Select::make('job_status')->label('Job Status')
                            ->options([
                                'Booking' => 'Booking',
                                'Quote' => 'Quote',
                            ])->required(),
                            Select::make('callback_status')
                            ->label('Callback Status')
                            ->options([
                                'Booked' => 'Booked',
                                'Pending' => 'Pending',
                                'New' => 'New',
                                'Lost' => 'Lost',
                            ])->required(),
                            Datepicker::make('callback_date')->label('Callback Date')->required(),
                            TextInput::make('location')->label('Location')->required(),
                        ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('quote')->searchable(),
            TextColumn::make('enquiry_date')->searchable(),
            TextColumn::make('booking_date')->searchable(),
            TextColumn::make('callback_date')->searchable(),
            TextColumn::make('job_status')->searchable(),
            TextColumn::make('job_status')
                    ->color(function (string $state) {
                        return match ($state) {
                            CallBackEnum::BOOKING => 'success',
                            CallBackEnum::QUOTE => 'warning',
                        };
                    })
                    ->formatStateUsing(fn (string $state): string => __("{$state}"))
                    ->weight('bold')
                    ->searchable(),
            TextColumn::make('location')->searchable(),
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
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCallbacks::route('/'),
            'create' => Pages\CreateCallbacks::route('/create'),
            'edit' => Pages\EditCallbacks::route('/{record}/edit'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            CallbacksResource\Widgets\CallbackList::class,
        ];
    }
}
