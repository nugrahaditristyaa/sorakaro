<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlashcardResource\Pages;
use App\Models\Flashcard;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlashcardResource extends Resource
{
    protected static ?string $model = Flashcard::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Flashcards';
    protected static ?string $modelLabel = 'Flashcard';
    protected static ?string $pluralModelLabel = 'Flashcards';
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationGroup = 'Flashcard';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('flashcard_category_id')
                ->label('Kategori')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('karo_word')
                ->label('Kata Karo')
                ->required()
                ->maxLength(255)
                ->placeholder('Mejuah-juah'),

            Forms\Components\TextInput::make('indonesian_translation')
                ->label('Terjemahan Indonesia')
                ->required()
                ->maxLength(255)
                ->placeholder('Halo / Salam sejahtera'),

            Forms\Components\Textarea::make('example_sentence')
                ->label('Contoh Kalimat (Karo)')
                ->rows(2)
                ->placeholder('Mejuah-juah, uga kabar?')
                ->columnSpanFull(),

            Forms\Components\Textarea::make('example_translation')
                ->label('Terjemahan Contoh Kalimat')
                ->rows(2)
                ->placeholder('Halo, apa kabar?')
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('karo_word')
                    ->label('Kata Karo')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('indonesian_translation')
                    ->label('Terjemahan')
                    ->sortable()
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('flashcard_category_id')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori')
                    ->preload(),
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
            'index' => Pages\ListFlashcards::route('/'),
            'create' => Pages\CreateFlashcard::route('/create'),
            'edit' => Pages\EditFlashcard::route('/{record}/edit'),
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
