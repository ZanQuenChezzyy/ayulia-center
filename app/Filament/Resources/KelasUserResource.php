<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PesertaSertifikasi;
use App\Filament\Resources\KelasUserResource\Pages;
use App\Filament\Resources\KelasUserResource\RelationManagers;
use App\Models\KelasUser;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        if ($user->hasRole('Peserta')) {
            return null;
        }
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        $user = Auth::user();
        if ($user->hasRole('Peserta')) {
            return null;
        }
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total Kelas Peserta';
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('')
                    ->schema([
                        Fieldset::make('Nama & Kelas Peserta')
                            ->schema([
                                ImageEntry::make('user.avatar_url')
                                    ->label('')
                                    ->circular(),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('user.name')
                                            ->label('Nama Peserta'),
                                        TextEntry::make('kelas.nama')
                                            ->label('Kelas Peserta')
                                    ])->columns(1),
                            ])->columns(2)
                            ->columnSpan(1),
                        Fieldset::make('Data Peserta')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        TextEntry::make('user.email')
                                            ->label('Email Peserta'),
                                        TextEntry::make('user.no_telepon')
                                            ->label('Nomor Telepon')
                                            ->prefix('+62 ')
                                    ]),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('user.tempat_lahir')
                                            ->label('Tempat, Tanggal Lahir')
                                            ->formatStateUsing(function (KelasUser $record) {
                                                Carbon::setLocale('id');
                                                $tanggalLahir = Carbon::parse($record->user->tanggal_lahir)->translatedFormat('d F Y');
                                                return $record->user->tempat_lahir . ', ' . $tanggalLahir;
                                            }),
                                        TextEntry::make('user.pendidikan_terakhir')
                                            ->label('Pendidikan Terakhir')
                                            ->badge()
                                            ->color('info')
                                            ->formatStateUsing(fn(int $state): string => match ($state) {
                                                0 => 'SD - Sekolah Dasar',
                                                1 => 'SMP - Sekolah Menengah Pertama',
                                                2 => 'SMA - Sekolah Menengah Atas',
                                                3 => 'D3 - Diploma 3',
                                                4 => 'S1 - Sarjana',
                                                5 => 'S2 - Magister',
                                                6 => 'S3 - Doktor',
                                                default => 'Lainnya',
                                            }),
                                    ])
                            ])->columns(2)
                            ->columnSpan(1)
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->query(
                KelasUser::query()->when(
                    $user->hasRole('Peserta'), // Periksa jika peran pengguna adalah 'Peserta'
                    function (Builder $query) use ($user) {
                        // Filter data hanya untuk kelas user saat ini
                        $query->where('user_id', $user->id);
                    }
                )
            )
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
