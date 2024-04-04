<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Filament\Resources\CallbacksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCallbacks extends ListRecords
{
    protected static string $resource = CallbacksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Call Back'),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            CallbacksResource\Widgets\CallbackList::class,
        ];
    }
}
