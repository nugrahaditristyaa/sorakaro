<?php

namespace App\Filament\Resources\GuidebookSectionResource\Pages;

use App\Filament\Resources\GuidebookSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuidebookSection extends EditRecord
{
    protected static string $resource = GuidebookSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
