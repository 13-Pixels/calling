<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Callback;
use App\Models\Customer;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;

use App\Enums\CallBackEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\CallbacksResource\Pages;
use App\Filament\Resources\CallbacksResource\RelationManagers;
use Illuminate\Support\Carbon;


class CallbacksResource extends Resource
{
    protected static ?string $model = Callback::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {

        
        // $customers = Customer::pluck('name', 'id');
        // if ($form->model == 'App\Models\Callback') {
        //     $customer = null;
        //     $job_status = null;
        // } else {
        //     $customer = Customer::findOrFail($form->model->customer);
        // }
        return $form
            ->schema([
                // Split::make([
                //     Section::make([
                //         Placeholder::make('customer_name')
                //             ->label('Customer Name')
                //             // ->content(isset($customer->name) ? $customer->name : null),
                //         Placeholder::make('customer_email')
                //             ->label('Customer Email')
                //             // ->content(isset($customer->email) ? $customer->email : null),
                //         Placeholder::make('customer_phone')
                //             ->label('Customer Phone')
                //             // ->content(isset($customer->phone) ? $customer->phone : null),
                //     ]),
                //     Section::make([
                //         Placeholder::make('pick_up')
                //             ->label('Collection Address')
                //             ->content(isset($customer->address) ? $customer->address : null),
                //         Placeholder::make('drop_off')
                //             ->label('Destination')
                //             ->content(isset($customer->destination) ? $customer->destination : null),
                //     ]),
                //     Section::make([
                //         Placeholder::make('enquiery')
                //             ->label('Enquiry Date')
                //             ->content(isset($customer->enquiry_date) ? $customer->enquiry_date : null),
                //         Placeholder::make('last_contact')
                //             ->label('Last Contact')
                //             ->content(isset($customer->phone) ? $customer->phone : null),
                //     ]),
                // ])
                //     ->columnSpan(4)
                //     ->hidden(fn(string $operation): bool => $operation === 'create'),
                // Split::make([
                //     Section::make([
                //         Placeholder::make('booking')
                //             ->label('Booking Date & Time')
                //             ->content(isset($customer->enquiry_date) ? $customer->enquiry_date : null),
                //     ]),
                //     Section::make([
                //         Placeholder::make('passengers')
                //             ->label('Number of Passengers')->content('3'),
                //         Placeholder::make('vehicle_type')
                //             ->label('Vehicle Type')->content('xyz'),
                //     ]),
                //     Section::make([
                //         Placeholder::make('total')
                //             ->label('Total Price')->content('123'),
                //         Placeholder::make('discount')
                //             ->label('Discounted Prices')->content('123'),
                //     ]),
                // ])
                //     ->columnSpanFull()
                //     ->hidden(fn(string $operation): bool => $operation === 'create'),
           
                Section::make('')
                    ->schema([
                        TextInput::make('quote')->label('Quote')
                        ->suffixAction(Action::make('Fetch')
                        ->button()
                        ->action(function($state, $set, $get) {
                            if (blank($state))
                                {
                                    Notification::make()
                                    ->title('Please enter number')
                                    ->danger()
                                    ->send();                                
                                }

                                try {
                                $http = new Http();
                                
                                    $data =Http::get('https://callbacks.savari.io/api/callback?quote=' . $get('quote'))->json();
                                
                                    //  dd($data['callbacks']['customer_name']);
                            
                                } catch (RequestException $e) {
                                    Filament::notify('danger', 'Unable to find the country');
                                    return;
                                }
                                
                        $set('customer_name', $data['callbacks']['customer_name'] ?? null);
                        $set('customer_email', $data['callbacks']['customer_email'] ?? null);
                        $set('customer_phone', $data['callbacks']['customer_phone'] ?? null);
                        $set('enquiry_date', $data['callbacks']['enquiry_date'] ?? null);
                        $set('booking_date', $data['callbacks']['booking_date'] ?? null);
                        $set('job_status', $data['callbacks']['job_status'] ?? null);
                        $set('callback_status', $data['callbacks']['callback_status'] ?? null);
                        $set('callback_date', $data['callbacks']['callback_date'] ?? null);
                        $set('pick_up', $data['callbacks']['pick_up'] ?? null);
                        $set('drop_off', $data['callbacks']['drop_off'] ?? null);
                        $set('via', $data['callbacks']['via'] ?? null);
                            })
                        ),

                        // Select::make('customer')->label('Customer')->options(Customer::pluck('name', 'id'))->required(),
                        TextInput::make('customer_name')->label('Customer name')->readOnly(),
                        TextInput::make('customer_email')->label('Customer Email')->required()->readOnly(),
                        TextInput::make('customer_phone')->label('Customer Phone')->readOnly(),
                        DatePicker::make('enquiry_date')->label('Enquiry Date')->required()->default(now()->toDateString()),
                        DatePicker::make('booking_date')->label('Booking Date')->required(),
                    ])
                    ->columns(2),
                    // ->hidden(fn(string $operation): bool => $operation === 'edit'),
                Section::make('')
                    ->schema([
                        // Split::make([
                        //     Placeholder::make('job_status')
                        //         ->label('Job Status')
                        //         ->content(fn (Callback $record): string => $record->job_status)
                        //         // ->color(['booking' => fn (?Callback $record): string => $record ? 'primary' : 'success']),
                        //             // new HtmlString("<strong><span style='color: " . ($job_status === 'booking' ? 'green' : ($job_status === 'quote' ? 'orange' : 'inherit')) . ";'>$job_status</span></strong>")),
                        // ])->hidden(fn(string $operation): bool => $operation === 'create'),

                        Select::make('job_status')->label('Job Status')
                            ->options([
                                'booking' => 'Booking',
                                'pending_quote' => 'Pending Quote',
                            ])->required()->default(['pending_quote']),
                            // ->hidden(fn(string $operation): bool => $operation === 'edit'),
                        Select::make('callback_status')
                            ->label('Callback Status')
                            ->options([
                                'booked' => 'Booked',
                                'pending_quote' => 'Pending Quote',
                                'new' => 'New',
                                'lost' => 'Lost',
                            ])
                            ->required()->default(['new']),
                            // ->default(''),
                        DatePicker::make('callback_date')->label('Callback Date')->required(),
                        TextInput::make('pick_up')->label('Pick Up')->required(),
                        TextInput::make('drop_off')->label('Drop Off')->required(),
                        TextInput::make('via')->label('via'),
                        TextInput::make('total')->label('Total Price')->numeric(),
                        TextInput::make('discount')->label('Discount Price')->numeric(),
                        // Select::make('location_id')->label('Location')->options($locations)->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quote')->searchable(),
                TextColumn::make('enquiry_date')->searchable()->dateTime('l jS F Y'),
                TextColumn::make('booking_date')->searchable()->dateTime('l jS F Y'),
                TextColumn::make('callback_date')->searchable()->dateTime('l jS F Y'),
                TextColumn::make('job_status')->searchable(),
                // TextColumn::make('job_status')
                //     ->color(function (string $state) {
                //         return match ($state) {
                //            'booking' => 'success',
                //             'quote' => 'warning',
                //         };
                //     })
                //     ->formatStateUsing(fn(string $state): string => __("{$state}"))
                //     ->weight('bold')
                //     ->searchable(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('callback_status')
                    ->color(function (string $state) {
                        return match ($state) {
                            'booked' => 'success',
                            'pending' => 'warning',
                            'new' => 'gray',
                            'lost' => 'danger',
                        };
                    })
                    ->formatStateUsing(fn(string $state): string => __("{$state}"))
                    ->weight('bold')
                    ->searchable(),
            ])
            ->filters([
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
