<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Filament\Resources\CallbacksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCallbacks extends EditRecord
{
    protected static string $resource = CallbacksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
