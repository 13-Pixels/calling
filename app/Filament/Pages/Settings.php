<?php

namespace App\Filament\Pages;

use App\Models\Settings as Setting;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Pages\Settings;


class Settings  extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';
 public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    
    //  public function form(Form $form): Form{
    //     return $form
    //         ->schema([
                
    //             Textarea::make('Callback mails')

    //         ])->statePath('data');;
    //     }

     public function form(Form $form): Form
    {
        return $form
            ->schema([
                Textarea::make('callback_mail')    
 ->default(fn(Setting $settings): string => $settings->first() ? $settings->pluck('callback_mail')->first() : '' )    ->autosize(),
                // ...
            ])
            ->statePath('data');
    }
    
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $mail = Setting::first();
            if (!$mail) {
                Setting::create($data);    
        }else{
            $mail->update($data);
        }
           
        } catch (Halt $exception) {
            return;
        }

         Notification::make() 
            ->success()
            ->title('Mail saved successfully')
            ->send(); 
    }

      public function create(): void
    {
        dd($this->form->getState());
    }

    
}
 