<?php

namespace App\Filament\Resources\CallbacksResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'Activity';

    public function form(Form $form): Form
    {
        $authenticatedUser = Auth::user();
        return $form
            ->schema([
                TextInput::make('user')
                ->label('User')
                ->readOnly()
                ->default($authenticatedUser->name),
                DatePicker::make('date')->required()->default(now()->toDateString()),
                Select::make('type')
                        ->label('Type')
                        ->options([           
                            'Call' => 'Call',
                            'SMS' => 'SMS',
                            'Email' => 'Email',
                        ])->required()->default('Call'),
                Textarea::make('update')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user')
            ->columns([
                TextColumn::make('user')->searchable(),
                TextColumn::make('date'),
                TextColumn::make('type'),
                TextColumn::make('update'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
               CreateAction::make()->label('New Activity'),
            ])
            ->actions([
               ViewAction::make()->color('warning'),
               DeleteAction::make(),

            ])
            ->bulkActions([
               BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    
}
