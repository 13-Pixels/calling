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
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Http;
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
                                            })
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
                            ->required()->default(['new']),
                            //     ->live()
                            // ->afterStateUpdated(function ($state, $set) {
                            //     if($state == 'lost' || 'booked') {
                            //         $set('callback_date', date('Y-m-d'));
                            //      };
                            // }),

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
