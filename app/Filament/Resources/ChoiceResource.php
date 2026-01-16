<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChoiceResource\Pages;
use App\Models\Choice;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ChoiceResource extends Resource
{
    protected static ?string $model = Choice::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Choices';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('question_id')
                ->label('Question')
                ->relationship('question', 'prompt')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('text')
                ->label('Choice text')
                ->required()
                ->maxLength(255),

            Toggle::make('is_correct')
                ->label('Correct answer?')
                ->default(false),

            TextInput::make('order')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('order')
            ->columns([
                TextColumn::make('question.prompt')
                    ->label('Question')
                    ->limit(40)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('text')
                    ->label('Choice')
                    ->searchable(),

                IconColumn::make('is_correct')
                    ->label('Correct')
                    ->boolean(),

                TextColumn::make('order')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChoices::route('/'),
            'create' => Pages\CreateChoice::route('/create'),
            'edit' => Pages\EditChoice::route('/{record}/edit'),
        ];
    }
}
