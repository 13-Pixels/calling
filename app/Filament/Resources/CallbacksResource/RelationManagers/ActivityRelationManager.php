<?php

namespace App\Filament\Resources\CallbacksResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityRelationManager extends RelationManager
{
    protected static string $relationship = 'activity';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user')->required()->maxLength(255),
                Forms\Components\Datepicker::make('date')->required(),//->maxLength(255),
                Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([           
                                'Call' => 'Call',
                                'SMS' => 'SMS',
                                'Email' => 'Email',
                            ])->required(),
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
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         // Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }
    
}
