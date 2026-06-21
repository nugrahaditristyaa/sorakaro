<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlashcardCategoryResource\Pages;
use App\Models\FlashcardCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlashcardCategoryResource extends Resource
{
    protected static ?string $model = FlashcardCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Kategori Flashcard';
    protected static ?string $modelLabel = 'Kategori Flashcard';
    protected static ?string $pluralModelLabel = 'Kategori Flashcard';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Flashcard';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(255)
                ->placeholder('Sapaan'),

            Forms\Components\TextInput::make('icon')
                ->label('Icon (emoji)')
                ->maxLength(10)
                ->placeholder('👋'),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->rows(2)
                ->maxLength(500)
                ->placeholder('Kata-kata sapaan dan salam dalam Bahasa Karo')
                ->columnSpanFull(),

            Forms\Components\TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->alignCenter()
                    ->width('60px'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('flashcards_count')
                    ->label('Jumlah Kata')
                    ->counts('flashcards')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlashcardCategories::route('/'),
            'create' => Pages\CreateFlashcardCategory::route('/create'),
            'edit' => Pages\EditFlashcardCategory::route('/{record}/edit'),
        ];
    }

    /**
     * Authorization: Only admin role can access
     */
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }
}
