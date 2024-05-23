<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Models\Mail;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Settings;
use App\Mail\CallbackMail;
use Filament\Actions\Action;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\CallbacksResource;

class EditCallbacks extends EditRecord
{
    protected static string $resource = CallbacksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('log')->url(fn (array $data): string => $this->getResource()::getUrl('log', ['record' => $this->getRecord()])),
             Action::make('Email')
          
                ->form([
                    // TextInput::make('subject')->required()->default(fn (Model $record): string => $record->drop_off)->disabled()->dehydrated(),
                    TextInput::make('to')->required()->default(fn (Model $record): string => $record->customer_email),
                    Select::make('mail')
                        ->options(Mail::query()->pluck('name', 'id'))
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set){
                            // dd($get('mail'));
                            $set('body', Mail::query()->where('id', $get('mail'))->pluck('template')->first());
                        })
                        ->searchable(),
                    Textarea::make('body')->required()->cols('20')->rows('15'),
            
                 ]) ->action(function (array $data) {
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
             
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($data['callback_status'] == 'lost' || $data['callback_status'] == 'booked') {
            $data['close_date'] = date('Y-m-d');
        };
        // dd($data['close_date']);
        $record->update($data);
        $this->refreshFormData([
                'close_date',
            ]);
    
        return $record;
    }
}
