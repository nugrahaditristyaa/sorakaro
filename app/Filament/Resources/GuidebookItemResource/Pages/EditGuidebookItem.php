<?php

namespace App\Filament\Resources\GuidebookItemResource\Pages;

use App\Filament\Resources\GuidebookItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuidebookItem extends EditRecord
{
    protected static string $resource = GuidebookItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
