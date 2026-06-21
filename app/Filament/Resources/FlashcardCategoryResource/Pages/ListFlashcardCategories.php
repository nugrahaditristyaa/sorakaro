<?php

namespace App\Filament\Resources\FlashcardCategoryResource\Pages;

use App\Filament\Resources\FlashcardCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFlashcardCategories extends ListRecords
{
    protected static string $resource = FlashcardCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
