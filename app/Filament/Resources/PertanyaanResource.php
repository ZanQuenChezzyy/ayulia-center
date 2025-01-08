<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PusatInformasi;
use App\Filament\Resources\PertanyaanResource\Pages;
use App\Filament\Resources\PertanyaanResource\RelationManagers;
use App\Models\Pertanyaan;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PertanyaanResource extends Resource
{
    protected static ?string $model = Pertanyaan::class;
    protected static ?string $cluster = PusatInformasi::class;
    protected static ?string $label = 'FAQ';
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-question-mark-circle';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total FAQ';
    protected static ?string $slug = 'pertanyaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('pertanyaan')
                                    ->label('Pertanyaan')
                                    ->placeholder('Masukkan Pertanyaan')
                                    ->minLength(10)
                                    ->maxLength(45)
                                    ->required(),
                                TextInput::make('jawaban')
                                    ->label('Jawaban')
                                    ->placeholder('Masukkan Jawaban')
                                    ->minLength(10)
                                    ->maxLength(60)
                                    ->required(),
                            ])->columnSpan(3),
                        Toggle::make('di_tampilkan')
                            ->label('Di Tampilkan ?')
                            ->inline(false)
                            ->required(),
                    ])->columns(4)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pertanyaan')
                    ->label('Pertanyaan & Jawaban')
                    ->description(fn(Pertanyaan $record): string => $record->jawaban)
                    ->limit(50),
                ToggleColumn::make('di_tampilkan')
                    ->label('Di Tampilkan ?'),
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
            'index' => Pages\ListPertanyaans::route('/'),
            'create' => Pages\CreatePertanyaan::route('/create'),
            'view' => Pages\ViewPertanyaan::route('/{record}'),
            'edit' => Pages\EditPertanyaan::route('/{record}/edit'),
        ];
    }
}
