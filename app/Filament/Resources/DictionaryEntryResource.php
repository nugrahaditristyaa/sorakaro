<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DictionaryEntryResource\Pages;
use App\Models\DictionaryEntry;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DictionaryEntryResource extends Resource
{
    protected static ?string $model = DictionaryEntry::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Kamus';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Kosakata')
                ->description('Kata Bahasa Karo dan artinya dalam Bahasa Indonesia.')
                ->schema([
                    TextInput::make('karo_word')
                        ->label('Kata Bahasa Karo')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('contoh: Horas')
                        // Unique validation: skip current record when editing
                        ->unique(
                            table: DictionaryEntry::class,
                            column: 'karo_word',
                            ignorable: fn ($record) => $record,
                        )
                        ->helperText('Setiap kata Karo harus unik — tidak boleh duplikat.'),

                    Textarea::make('indonesian_translation')
                        ->label('Arti Bahasa Indonesia')
                        ->required()
                        ->rows(2)
                        ->placeholder('contoh: Halo / Salam')
                        ->helperText('Bisa berisi beberapa arti, pisahkan dengan / atau koma.'),
                ]),

            Section::make('Contoh Kalimat (Opsional)')
                ->description('Tambahkan contoh kalimat untuk memperjelas penggunaan kata.')
                ->collapsible()
                ->schema([
                    Textarea::make('example_sentence')
                        ->label('Contoh Kalimat (Bahasa Karo)')
                        ->nullable()
                        ->rows(2)
                        ->placeholder('contoh: Horas kam!'),

                    Textarea::make('example_translation')
                        ->label('Terjemahan Contoh (Bahasa Indonesia)')
                        ->nullable()
                        ->rows(2)
                        ->placeholder('contoh: Halo kamu!'),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('karo_word')
            ->columns([
                TextColumn::make('karo_word')
                    ->label('Kata Karo')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('indonesian_translation')
                    ->label('Arti Indonesia')
                    ->limit(60)
                    ->searchable(),

                IconColumn::make('example_sentence')
                    ->label('Contoh')
                    ->boolean()
                    ->trueIcon('heroicon-o-chat-bubble-left-ellipsis')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable()
                    ->color('gray'),
            ])
            ->filters([])
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDictionaryEntries::route('/'),
            'create' => Pages\CreateDictionaryEntry::route('/create'),
            'edit'   => Pages\EditDictionaryEntry::route('/{record}/edit'),
        ];
    }
}
