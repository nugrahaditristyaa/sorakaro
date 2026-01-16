<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidebookSectionResource\Pages;
use App\Filament\Resources\GuidebookSectionResource\RelationManagers;
use App\Models\GuidebookSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GuidebookSectionResource extends Resource
{
    protected static ?string $model = GuidebookSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Guidebook Sections';
    protected static ?string $modelLabel = 'Guidebook Section';
    protected static ?string $pluralModelLabel = 'Guidebook Sections';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Guidebook';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('level_id')
                ->label('Level')
                ->relationship('level', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('title')
                ->label('Title (e.g., KEY PHRASES, GRAMMAR TIPS)')
                ->required()
                ->maxLength(255)
                ->placeholder('KEY PHRASES'),

            Forms\Components\TextInput::make('subtitle')
                ->label('Subtitle')
                ->maxLength(255)
                ->placeholder('Discuss traveling solo'),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(3)
                ->columnSpanFull(),

            Forms\Components\TextInput::make('order')
                ->label('Display Order')
                ->numeric()
                ->default(0)
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('level.name')
                    ->label('Level')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Subtitle')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->relationship('level', 'name')
                    ->label('Filter by Level')
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
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
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGuidebookSections::route('/'),
            'create' => Pages\CreateGuidebookSection::route('/create'),
            'edit' => Pages\EditGuidebookSection::route('/{record}/edit'),
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
