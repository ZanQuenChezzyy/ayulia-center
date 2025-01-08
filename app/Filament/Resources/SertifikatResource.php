<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PesertaSertifikasi;
use App\Filament\Resources\SertifikatResource\Pages;
use App\Filament\Resources\SertifikatResource\RelationManagers;
use App\Models\KelasUser;
use App\Models\Sertifikat;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SertifikatResource extends Resource
{
    protected static ?string $model = Sertifikat::class;
    protected static ?string $cluster = PesertaSertifikasi::class;
    protected static ?string $label = 'Sertifikat Peserta';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $activeNavigationIcon = 'heroicon-s-trophy';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total Sertifikasi Peserta';
    protected static ?string $slug = 'sertifikat-peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('kelas_user_id')
                            ->label('Peserta')
                            ->placeholder('Pilih Peserta')
                            ->relationship('kelasUser', 'id')
                            ->getOptionLabelFromRecordUsing(fn(KelasUser $record) => $record->user->name . ' - ' . $record->kelas->nama)
                            ->native(false)
                            ->preload()
                            ->searchable(['kelasUser.user.name'])
                            ->required(),
                        FileUpload::make('file')
                            ->label('File Sertifikat')
                            ->image()
                            ->imageEditor()
                            ->visibility('public')
                            ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kelasUser.id')
                    ->label('Peserta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('file')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
            'index' => Pages\ListSertifikats::route('/'),
            'create' => Pages\CreateSertifikat::route('/create'),
            'view' => Pages\ViewSertifikat::route('/{record}'),
            'edit' => Pages\EditSertifikat::route('/{record}/edit'),
        ];
    }
}
