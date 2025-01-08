<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Kelas')
                    ->schema([
                        Select::make('instruktur_id')
                            ->label('Instruktur Kelas')
                            ->placeholder('Pilih Instruktur Kelas')
                            ->relationship('instruktur', 'nama')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('nama')
                            ->label('Nama Kelas')
                            ->placeholder('Masukkan Nama Kelas')
                            ->minLength(3)
                            ->maxLength(45)
                            ->required()
                            ->maxLength(45),
                        TextInput::make('tingkatan')
                            ->label('Tingkatan Kelas')
                            ->placeholder('Masukkan Tingkatan Kelas')
                            ->minLength(3)
                            ->maxLength(45)
                            ->required()
                            ->maxLength(45),
                    ])->columns(2)
                    ->columnSpan(1),

                Section::make('Waktu Kelas')
                    ->schema([
                        TextInput::make('jumlah pertemuan')
                            ->label('Jumlah Pertemuan')
                            ->placeholder('Masukkan Jumlah')
                            ->suffix('Kali')
                            ->minValue(1)
                            ->maxValue(99)
                            ->minLength(1)
                            ->maxLength(2)
                            ->numeric()
                            ->required(),
                        TextInput::make('harga')
                            ->label('Harga Kelas')
                            ->placeholder('Harga Kelas')
                            ->prefix('Rp')
                            ->suffix(',00')
                            ->minValue(1000)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->required(),
                        TimePicker::make('jam_mulai')
                            ->label('Jam Kelas Mulai')
                            ->placeholder('Pilih Jam Mulai')
                            ->native(false)
                            ->suffix('WITA')
                            ->required(),
                        TimePicker::make('jam_selesai')
                            ->label('Jam Kelas Selesai')
                            ->placeholder('Pilih Jam Selesai')
                            ->native(false)
                            ->suffix('WITA')
                            ->required(),
                    ])->columns(2)
                    ->columnSpan(1),

                Section::make()
                    ->schema([
                        Textarea::make('deskripsi')
                            ->required()
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('instruktur.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tingkatan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah pertemuan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jam_mulai'),
                Tables\Columns\TextColumn::make('jam_selesai'),
                Tables\Columns\TextColumn::make('harga')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
