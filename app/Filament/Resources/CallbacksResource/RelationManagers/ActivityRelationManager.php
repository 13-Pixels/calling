<?php

namespace App\Filament\Resources\CallbacksResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'Activity';

    public function form(Form $form): Form
    {
        $authenticatedUser = Auth::user();
        return $form
            ->schema([
                Forms\Components\TextInput::make('user')
                ->label('User')
                ->readOnly()
                ->default($authenticatedUser->name),
                Forms\Components\DatePicker::make('date')->required()->default(now()->toDateString()),
                Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([           
                                'Call' => 'Call',
                                'SMS' => 'SMS',
                                'Email' => 'Email',
                            ])->required()->default('Call'),
                Forms\Components\TextInput::make('update')->required()->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user')
            ->columns([
                Tables\Columns\TextColumn::make('user')->searchable(),
                Tables\Columns\TextColumn::make('date'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('update'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Activity'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('warning'),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         // Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }
    
}
