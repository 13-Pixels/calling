<?php

namespace App\Filament\Resources\CallbacksResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CallbacksResource;

class EditCallbacks extends EditRecord
{
    protected static string $resource = CallbacksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Action::make('log')->url(fn (array $data): string => $this->getResource()::getUrl('log', ['record' => $this->getRecord()]))
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if($data['callback_status'] == 'lost' || $data['callback_status'] == 'booked') {
            $data['close_date'] = date('Y-m-d');
        };
        // dd($data['close_date']);
        $record->update($data);
        $this->refreshFormData([
                'close_date',
            ]);
    
        return $record;
    }
}
