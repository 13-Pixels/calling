<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use App\Models\Callback;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Settings;

use Filament\Forms\Form;
use App\Mail\CallbackMail;
use Filament\Tables\Table;
use App\Enums\CallBackEnum;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\CallbacksResource\Pages;
use App\Filament\Resources\CallbacksResource\RelationManagers;


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

                        TextInput::make('quote')->label('Quote')->required()
                        ->suffixActions([
                            Action::make('Fetch')
                                ->button()
                                ->action(function($state, $set, $get) {
                                    if (blank($state))
                                        {
                                            Notification::make()
                                            ->title('Please enter quote')
                                            ->danger()
                                            ->send();                                
                                        }else{
                                            try {
                                        $http = new Http();
                                            $data =Http::get('https://operator.savari.io/web_api_v2.php?company_name=omc&action=show&id=' . $get('quote'))->json();
                                            if(!$data){
                                                Notification::make()
                                            ->title('Quote not exist')
                                            ->danger()
                                            ->send();  
                                            }
                                            $set('customer_name', $data['0']['name'] ?? null);
                                            $set('customer_email', $data['0']['email'] ?? null);
                                            $set('customer_phone', $data['0']['telephone'] ?? null);
                                            $set('enquiry_date',  date('Y-m-d') ?? null);
                                            if($data){
                                                $set('booking_date',  date('Y-m-d',$data['0']['job_date']) );
                                            }else{
                                                $set('booking_date', null);
                                            }
                                            if($data){
                                                   if($data[0]['job_Status'] == 0 || 10){
                                                $set('job_status', 'pending_quote');
                                            }else{
                                                $set('job_status', 'booking');
                                            }
                                            }
                                            $set('callback_status', $data['0']['callback_status'] ?? null);
                                            $set('callback_date', date('Y-m-d') ?? null);
                                            $set('pick_up', $data['0']['pickup'] ?? null);
                                            $set('drop_off', $data['0']['destination'] ?? null);
                                            $set('total', $data['0']['total_not_refer'] ?? null);
                                            $set('discount', $data['0']['discount'] ?? null);                            
                                        } catch (RequestException $e) {
                                            return $e;
                                        }
                                        }
                                     
                                    }),
                                    Action::make('Open in savari')
                                        ->button()
                                        ->action(function($state) {
                                            if (blank($state)){
                                                Notification::make()
                                                ->title('Please enter quote')
                                                ->danger()
                                                ->send();                                
                                            }
                                            else{                                                 
                                                return redirect()->away('https://operator.savari.io/job.php?action=edit&id=' . $state . '&list=new');
                                            }
                                            }),
                                        ]),
               
                        // Select::make('customer')->label('Customer')->options(Customer::pluck('name', 'id'))->required(),
                        TextInput::make('customer_name')->label('Customer name')->disabled()->dehydrated(),
                        TextInput::make('customer_email')->label('Customer Email')->required()->disabled()->dehydrated(),
                        TextInput::make('customer_phone')->label('Customer Phone')->disabled()->dehydrated(),
                        DatePicker::make('enquiry_date')->label('Enquiry Date')->required()->default(now()->toDateString()),
                        DatePicker::make('booking_date')->label('Booking Date')->required(),
                    ])
                    ->columns(2),
                    // ->hidden(fn(string $operation): bool => $operation === 'edit'),
                     Fieldset::make()
                        ->schema([
                             Select::make('job_status')->label('Job Status')
                            ->options([
                                'booking' => 'Booking',
                                'pending_quote' => 'Pending Quote',
                            ])->required()->default(['pending_quote'])->columns(3),
                            // ->hidden(fn(string $operation): bool => $operation === 'edit'),
                        Select::make('callback_status')
                            ->label('Callback Status')
                            ->options([
                                'booked' => 'Booked',
                                'pending_quote' => 'Pending Quote',
                                'new' => 'New',
                                'lost' => 'Lost',
                            ])
                            ->required()->default(['new'])->live(),
                            //     ->live()
                            // ->afterStateUpdated(function ($state, $set) {
                            //     if($state == 'lost' || 'booked') {
                            //         $set('callback_date', date('Y-m-d'));
                            //      };
                            // }),
                            Select::make('cancel_reason')
                                ->options(fn (Get $get): array => match ($get('callback_status')) {
                                    'lost' => [
                                        'price_was_high' => 'Price was high',
                                        'booked_elsewhere' => 'Booked elsewhere',
                                        'plan_cancelled' => 'Plan cancelled',
                                        'booked_on_other_quote' => 'Booked on other quote',
                                        'booked_with_competitor' => 'Booked with competitor',
                                        'duplicate_job' => 'Duplicate job',
                                        'no_contract' => 'No contract',
                                        'journey_not_required' => 'Journey not required',
                                        'payment_terms' => 'Payment terms',
                                        'price' => 'Price',
                                        'unable_to_meet_deadline' => 'Unable to meet deadline',
                                        'others' => 'Others',
                                        'unsuitable_supply' => 'Unsuitable supply',
                                    ],
                                    default => [],
                                }),

                            DatePicker::make('callback_date')->label('Callback Date')->required()->default(now()->toDateString()),
                        ])->columns(3),
                Section::make('')
                    ->schema([
                        // Split::make([
                        //     Placeholder::make('job_status')
                        //         ->label('Job Status')
                        //         ->content(fn (Callback $record): string => $record->job_status)
                        //         // ->color(['booking' => fn (?Callback $record): string => $record ? 'primary' : 'success']),
                        //             // new HtmlString("<strong><span style='color: " . ($job_status === 'booking' ? 'green' : ($job_status === 'quote' ? 'orange' : 'inherit')) . ";'>$job_status</span></strong>")),
                        // ])->hidden(fn(string $operation): bool => $operation === 'create'),
                       
                       
                        TextInput::make('pick_up')->label('Pick Up')->required(),
                        TextInput::make('drop_off')->label('Drop Off')->required(),
                        TextInput::make('total')->label('Total Price')->disabled()->dehydrated(),
                        TextInput::make('discount')->label('Discount Price')->numeric(),
                        TextInput::make('close_date')->label('Close Date')->disabled(),
                        // Select::make('location_id')->label('Location')->options($locations)->required(),
                    ])->columns(2),
            ]);
    }

      public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('quote')->searchable()->sortable(),
                TextColumn::make('enquiry_date')->searchable()->dateTime('l jS F Y')->sortable(),
                TextColumn::make('booking_date')->searchable()->dateTime('l jS F Y')->sortable(),
                TextColumn::make('callback_date')->searchable()->dateTime('l jS F Y')->sortable(),
                TextColumn::make('job_status')->searchable()
                ->formatStateUsing(function (string $state) {
                    return match ($state) {
                        'booking' => 'Booking',
                        'pending_quote' => 'Pending Quote',
                    };
                })->searchable()->sortable(),
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
                TextColumn::make('callback_status')
                    ->formatStateUsing(function (string $state) {
                        return match ($state) {
                            'booked' => 'Booked',
                            'pending_quote' => 'Pending Quote',
                            'new' => 'New',
                            'lost' => 'Lost',
                        };
                    })
                    ->searchable()->sortable(),
                TextColumn::make('total')->prefix('₤'),
                TextColumn::make('close_date'),

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
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\Action::make('Email')
                ->form([
                    // TextInput::make('subject')->required()->default(fn (Model $record): string => $record->drop_off)->disabled()->dehydrated(),
                    TextInput::make('to')->required()->default(fn (Model $record): string => $record->customer_email),
                    TextInput::make('subject'),
                    Textarea::make('body')->required()
                    ->default(fn(Settings $settings): string => $settings->first() ? $settings->pluck('callback_mail')->first() : '')    ->autosize(),
                ])
                ->action(function (array $data) {
                    // dd($data);

                    // dd($data);
           
                         Mail::to($data['to'])
            ->send(new CallbackMail(
                // subject: $data['subject'],
                 $data['body'],
            ));
             Notification::make()
            ->title('Mail sent successfully')
            ->success()
            ->send();
                })
             
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
            'log' => Pages\Logs::route('/{record}/log'),
        ];
    }
    public static function getWidgets(): array
    {
        return [
            CallbacksResource\Widgets\CallbackList::class,
        ];
    }
}