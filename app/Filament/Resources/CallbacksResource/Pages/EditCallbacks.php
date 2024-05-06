<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use App\Filament\Resources\CallbacksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditCallbacks extends EditRecord
{
    protected static string $resource = CallbacksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
{
    if($data['callback_status'] == 'lost' || $data['callback_status'] == 'booked') {
        $data['close_date'] = date('Y-m-d');
    };
    // dd($data['close_date']);
    $record->update($data);
 
    return $record;
}
}
