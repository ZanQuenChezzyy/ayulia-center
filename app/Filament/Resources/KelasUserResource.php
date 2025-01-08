<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PesertaSertifikasi;
use App\Filament\Resources\KelasUserResource\Pages;
use App\Filament\Resources\KelasUserResource\RelationManagers;
use App\Models\KelasUser;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasUserResource extends Resource
{
    protected static ?string $model = KelasUser::class;
    protected static ?string $cluster = PesertaSertifikasi::class;
    protected static ?string $label = 'Kelas Peserta';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $activeNavigationIcon = 'heroicon-s-book-open';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total Peserta';
    protected static ?string $slug = 'kelas-peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kelas Peserta')
                    ->schema([
                        Select::make('user_id')
                            ->label('Nama Peserta')
                            ->placeholder('Pilih Peserta')
                            ->relationship('user', 'name')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required(),
                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->placeholder('Pilih Kelas')
                            ->relationship('kelas', 'nama')
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required(),
                    ])->columns(2)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Peserta')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->icon('heroicon-o-ellipsis-horizontal-circle')
                    ->color('info')
                    ->tooltip('Aksi')
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
            'index' => Pages\ListKelasUsers::route('/'),
            'create' => Pages\CreateKelasUser::route('/create'),
            'view' => Pages\ViewKelasUser::route('/{record}'),
            'edit' => Pages\EditKelasUser::route('/{record}/edit'),
        ];
    }
}
