<?php

namespace App\Filament\Resources\FlashcardCategoryResource\Pages;

use App\Filament\Resources\FlashcardCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFlashcardCategory extends EditRecord
{
    protected static string $resource = FlashcardCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
