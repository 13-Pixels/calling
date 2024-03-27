<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Callback;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\CallBackEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\CallbacksResource\Pages;
use App\Filament\Resources\CallbacksResource\RelationManagers;


class CallbacksResource extends Resource
{
    protected static ?string $model = Callback::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        $customer = Customer::findOrFail($form->model->customer_id);
        $customers = Customer::pluck('name', 'id');
        return $form
            ->schema([
                Split::make([
                    Section::make([
                        Placeholder::make('name')
                            ->label('Customer Name')
                            ->content($customer->name),
                        Placeholder::make('name')
                            ->label('Customer Email')
                            ->content($customer->email),
                        Placeholder::make('name')
                            ->label('Customer Phone')
                            ->content($customer->phone),
                    ]),
                    Section::make([
                        Placeholder::make('name')
                            ->label('Collection Address')->content($customer->address),
                        Placeholder::make('Email')
                            ->label('Destination')->content($customer->destination),
                    ]),
                    Section::make([
                        Placeholder::make('name')
                            ->label('Enquiry Date')->content($customer->enquiry_date),
                        Placeholder::make('Email')
                            ->label('Last Contact')->content($customer->phone),
                    ]),
                ])
                ->columnSpan(4)
                ->hidden(fn (string $operation): bool => $operation === 'create'),

                Split::make([
                    Section::make([
                        Placeholder::make('name')
                            ->label('Booking Date & Time')->content($customer->enquiry_date),                   
                    ]),
                    Section::make([
                        Placeholder::make('name')
                            ->label('Number of Passengers')->content('3'),
                        Placeholder::make('Email')
                            ->label('Vehicle Type')->content('xyz'),
                    ]),
                    Section::make([
                        Placeholder::make('name')
                            ->label('Total Price')->content('123'),
                        Placeholder::make('Email')
                            ->label('Discounted Prices')->content($customer->name),
                    ]),
                ])
                ->columnSpanFull()
                ->hidden(fn (string $operation): bool => $operation === 'create'),

                Section::make('')
                    ->schema([
                        TextInput::make('quote')->label('Quote #')->required(),
                        //Select::make('customer_id')->label('Customer')->options($customers)->required(),
                        DatePicker::make('enquiry_date')->label('Enquiry Date')->required(),
                        DatePicker::make('booking_date')->label('Booking Date')->required(),
                    ])
                    ->columns(2)
                    ->hidden(fn (string $operation): bool => $operation === 'edit'),
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
                        DatePicker::make('callback_date')->label('Callback Date')->required(),
                        TextInput::make('location')->label('Location')->required(),
                    ])->columns(2),
                ]);
            
             $custdata = Customer::findOrFail($customerId);
             $form->getModel()->customer = $custdata;
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
