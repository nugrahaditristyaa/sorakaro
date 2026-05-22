<?php

namespace App\Filament\Resources\DictionaryEntryResource\Pages;

use App\Filament\Resources\DictionaryEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDictionaryEntry extends EditRecord
{
    protected static string $resource = DictionaryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
