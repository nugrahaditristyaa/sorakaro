<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Lessons';
    protected static ?string $navigationGroup = 'Quiz';
    protected static ?string $modelLabel = 'Lesson';
    protected static ?string $pluralModelLabel = 'Lessons';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('level_id')
                ->label('Level')
                ->relationship('level', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            TextInput::make('order')
                ->label('Order')
                ->numeric()
                ->default(0),

            TextInput::make('pass_rate')
                ->label('Pass Rate (%)')
                ->numeric()
                ->default(70)
                ->minValue(0)
                ->maxValue(100)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                TextColumn::make('level.name')
                    ->label('Level')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('pass_rate')
                    ->label('Pass Rate')
                    ->formatStateUsing(fn (string $state): string => "{$state}%")
                    ->sortable(),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
