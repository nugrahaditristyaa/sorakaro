<?php

namespace App\Filament\Resources\GuidebookItemResource\Pages;

use App\Filament\Resources\GuidebookItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuidebookItems extends ListRecords
{
    protected static string $resource = GuidebookItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
