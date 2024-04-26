<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Filament\Resources\CallbacksResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCallbacks extends CreateRecord
{
    protected static string $resource = CallbacksResource::class;

    protected function getFormSchema(): array
{
    return [
        TextInput::make('country_name'),
        TextInput::make('country_region'),
        TextInput::make('country_subregion'),
    ];
}
}
