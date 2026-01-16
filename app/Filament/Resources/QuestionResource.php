<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Questions';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('lesson_id')
                ->label('Lesson')
                ->relationship('lesson', 'title')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('type')
                ->options([
                    'mcq' => 'Multiple Choice',
                    'typing' => 'Typing',
                ])
                ->default('mcq')
                ->required(),

            Textarea::make('prompt')
                ->label('Question')
                ->required()
                ->columnSpanFull(),

            Textarea::make('explanation')
                ->label('Explanation (optional)')
                ->columnSpanFull(),

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
                TextColumn::make('lesson.title')
                    ->label('Lesson')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('prompt')
                    ->label('Question')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
