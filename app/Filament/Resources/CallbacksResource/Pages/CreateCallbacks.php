<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Filament\Resources\CallbacksResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;


class CreateCallbacks extends CreateRecord
{
    protected static string $resource = CallbacksResource::class;

protected function handleRecordCreation(array $data): Model
{
    if($data['callback_status'] == 'lost' || $data['callback_status'] == 'booked') {
        $data['close_date'] = date('Y-m-d');
    };
    // dd($data['close_date']);
    return static::getModel()::create($data);
}
}
