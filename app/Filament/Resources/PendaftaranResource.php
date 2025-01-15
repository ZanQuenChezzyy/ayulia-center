<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;
    protected static ?string $label = 'Peserta';
    protected static ?string $navigationGroup = 'Pusat Informasi';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $activeNavigationIcon = 'heroicon-s-user';
    protected static ?int $navigationSort = 5;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total Peserta';
    protected static ?string $slug = 'peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Formulir Pendaftaran')
                    ->schema([
                        Group::make()
                            ->schema([
                                Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->placeholder('Pilih Kelas')
                                    ->relationship('kelas', 'nama')
                                    ->native(false)
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('nama')
                                    ->label('Nama Peserta')
                                    ->placeholder('Masukkan Nama Peserta')
                                    ->minLength(3)
                                    ->maxLength(45)
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('no_telepon')
                                    ->label('Nomor Telepon')
                                    ->placeholder('Masukkan Nomor Telepon')
                                    ->prefix('+62')
                                    ->tel()
                                    ->maxLength(15)
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email Peserta')
                                    ->placeholder('Masukkan Email Peserta')
                                    ->email()
                                    ->maxLength(45)
                                    ->columnSpanFull()
                                    ->required(),
                            ])->columns(2)
                            ->columnSpan(1),
                        Group::make()
                            ->schema([
                                Select::make('pendidikan_terakhir')
                                    ->label('Pendidikan Terakhir')
                                    ->placeholder('Pilih pendidikan terakhir')
                                    ->options([
                                        0 => 'SD - Sekolah Dasar',
                                        1 => 'SMP - Sekolah Menengah Pertama',
                                        2 => 'SMA - Sekolah Menengah Atas',
                                        3 => 'D3 - Diploma 3',
                                        4 => 'S1 - Sarjana',
                                        5 => 'S2 - Magister',
                                        6 => 'S3 - Doktor',
                                    ])
                                    ->native(false)
                                    ->preload()
                                    ->searchable()
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->placeholder('Masukkan Tempat Lahir')
                                    ->maxLength(45)
                                    ->required(),
                                DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->placeholder('Pilih Tanggal Lahir')
                                    ->native(false)
                                    ->required(),
                                Textarea::make('alamat')
                                    ->label('Alamat')
                                    ->placeholder('Masukkan Alamat')
                                    ->required()
                                    ->columnSpanFull()
                                    ->rows(5),
                            ])->columns(2)
                            ->columnSpan(1),
                    ])->columns(2)
                    ->columnSpan(fn() => Auth::user()->can('Ubah Status Pendaftaran') ? 3 : 4),
                Section::make('Status Pendaftaran')
                    ->schema([
                        Select::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->placeholder('Pilih Status Pembayaran')
                            ->options([
                                0 => 'Menunggu',
                                1 => 'Sudah Bayar',
                                2 => 'Belum Bayar',
                            ])
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required()
                            ->hidden(fn() => !Auth::user()->can('Ubah Status Pendaftaran'))
                            ->default(0),
                        Select::make('status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->placeholder('Pilih Status Pendaftaran')
                            ->options([
                                0 => 'Menunggu',
                                1 => 'Diterima',
                                2 => 'Ditolak',
                            ])
                            ->native(false)
                            ->preload()
                            ->searchable()
                            ->required()
                            ->hidden(fn() => !Auth::user()->can('Ubah Status Pendaftaran'))
                            ->default(0),
                    ])->columns(1)
                    ->columnSpan(1)
                    ->hidden(fn() => !Auth::user()->can('Ubah Status Pendaftaran')),
                Section::make('Berkas Pendaftaran')
                    ->schema([
                        FileUpload::make('ktp_url')
                            ->label('Kartu Tanda Penduduk')
                            ->image()
                            ->imageEditor()
                            ->visibility('public')
                            ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                            ->required(),
                        FileUpload::make('avatar_url')
                            ->label('Foto Latar Merah')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                            ])
                            ->imageCropAspectRatio('1:1')
                            ->directory('foto_instruktur')
                            ->visibility('public')
                            ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                            ->required(),
                        FileUpload::make('bukti_pembayaran')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->imageEditor()
                            ->visibility('public')
                            ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                            ->required(),
                    ])->columns(3)
                    ->columnSpanFull()
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Peserta')
                    ->formatStateUsing(function (Pendaftaran $record) {
                        $nameParts = explode(' ', trim($record->nama));
                        $initials = isset($nameParts[1])
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($nameParts[0], 0, 1));
                        $fotoUrl = $record->avatar_url
                            ? asset('storage/' . $record->avatar_url)
                            : 'https://ui-avatars.com/api/?name=' . $initials . '&amp;color=FFFFFF&amp;background=030712';
                        $image = '<img class="w-10 h-10 rounded-lg" style="margin-right: 0.625rem !important;" src="' . $fotoUrl . '" alt="Avatar User">';
                        $nama = '<strong class="text-sm font-medium text-gray-800">' . e($record->nama) . '</strong>';
                        $noTelepon = '<span class="text-sm text-gray-500 dark:text-gray-400">' . e($record->email) . ' | ' . ' +62 ' . e($record->no_telepon) . '</span>';
                        return '<div class="flex items-center" style="margin-right: 4rem !important">'
                            . $image
                            . '<div>' . $nama . '<br>' . $noTelepon . '</div></div>';
                    })
                    ->html()
                    ->sortable(['nama'])
                    ->searchable(['nama', 'no_telepon', 'email']),
                TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('tempat_lahir')
                    ->label('Tempat, Tanggal Lahir')
                    ->formatStateUsing(function (Pendaftaran $record) {
                        Carbon::setLocale('id');
                        $tanggalLahir = Carbon::parse($record->tanggal_lahir)->translatedFormat('d F Y');
                        return $record->tempat_lahir . ', ' . $tanggalLahir;
                    })
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pendidikan_terakhir')
                    ->label('Pendidikan')
                    ->badge()
                    ->formatStateUsing(fn(int $state): string => match ($state) {
                        0 => 'SD - Sekolah Dasar',
                        1 => 'SMP - Sekolah Menengah Pertama',
                        2 => 'SMA - Sekolah Menengah Atas',
                        3 => 'D3 - Diploma 3',
                        4 => 'S1 - Sarjana',
                        5 => 'S2 - Magister',
                        6 => 'S3 - Doktor',
                        default => 'Lainnya',
                    })
                    ->color('info'),
                ImageColumn::make('ktp_url')
                    ->label('Berkas KTP')
                    ->square()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('avatar_url')
                    ->label('Berkas Latar Merah')
                    ->square()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('bukti_pembayaran')
                    ->label('Berkas Bukti Pembayaran')
                    ->square()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                SelectColumn::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        0 => 'Menunggu',
                        1 => 'Sudah Bayar',
                        2 => 'Belum Bayar',
                    ])
                    ->hidden(fn() => !Auth::user()->can('Ubah Status Pendaftaran')),
                SelectColumn::make('status_pendaftaran')
                    ->label('Status Pendaftaran')
                    ->options([
                        0 => 'Menunggu',
                        1 => 'Sudah Bayar',
                        2 => 'Belum Bayar',
                    ])
                    ->hidden(fn() => !Auth::user()->can('Ubah Status Pendaftaran')),
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'view' => Pages\ViewPendaftaran::route('/{record}'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
