<?php

namespace App\Filament\Resources\GuidebookSectionResource\Pages;

use App\Filament\Resources\GuidebookSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGuidebookSections extends ListRecords
{
    protected static string $resource = GuidebookSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
