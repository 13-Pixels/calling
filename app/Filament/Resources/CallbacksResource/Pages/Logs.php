<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Models\Callback;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Resources\CallbacksResource;
use Filament\Tables\Concerns\InteractsWithTable;


class Logs extends Page implements HasTable
{
    protected static string $resource = CallbacksResource::class;

    protected static string $view = 'filament.resources.callbacks-resource.pages.logs';
    
    use InteractsWithTable;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Callback::query())
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('quote'),
                TextColumn::make('job_status'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

}
