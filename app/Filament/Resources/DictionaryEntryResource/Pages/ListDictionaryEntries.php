<?php

namespace App\Filament\Resources\DictionaryEntryResource\Pages;

use App\Filament\Resources\DictionaryEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDictionaryEntries extends ListRecords
{
    protected static string $resource = DictionaryEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
