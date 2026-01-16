<?php

namespace App\Filament\Resources\GuidebookSectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Guidebook Items';
    protected static ?string $recordTitleAttribute = 'text';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')
                ->label('Type')
                ->options([
                    'phrase' => '💬 Phrase',
                    'tip' => '💡 Tip',
                ])
                ->required()
                ->default('phrase'),

            Forms\Components\Textarea::make('text')
                ->label('Main Text')
                ->required()
                ->rows(2)
                ->columnSpanFull()
                ->placeholder('Hello, how are you?'),

            Forms\Components\Textarea::make('translation')
                ->label('Translation')
                ->rows(2)
                ->columnSpanFull()
                ->placeholder('Halo, apa kabar?'),

            Forms\Components\TextInput::make('audio_path')
                ->label('Audio Path (Future)')
                ->maxLength(255)
                ->placeholder('/audio/hello.mp3'),

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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('text')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'phrase' => 'info',
                        'tip' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'phrase' => '💬 Phrase',
                        'tip' => '💡 Tip',
                    }),

                Tables\Columns\TextColumn::make('text')
                    ->label('Text')
                    ->limit(60)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('translation')
                    ->label('Translation')
                    ->limit(50),

                Tables\Columns\TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'phrase' => 'Phrase',
                        'tip' => 'Tip',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
