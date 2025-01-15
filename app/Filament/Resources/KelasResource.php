<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset as InfoFieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $label = 'Kelas';
    protected static ?string $navigationGroup = 'Instrukutur & Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $activeNavigationIcon = 'heroicon-s-bookmark-square';
    protected static ?int $navigationSort = 3;
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
    protected static ?string $navigationBadgeTooltip = 'Total Kelas';
    protected static ?string $slug = 'kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Data Kelas')
                    ->schema([
                        Fieldset::make('Data Kelas')
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

                        Fieldset::make('Data Waktu Kelas')
                            ->schema([
                                TextInput::make('jumlah_pertemuan')
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
                        RichEditor::make('deskripsi')
                            ->label('Deskripsi Kelas')
                            ->placeholder('Masukkan Deskripsi Kelas')
                            ->disableToolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'codeBlock',
                                'link',
                                'strike',
                            ])
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpanFull()
                    ->columns(2)
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfoSection::make('')
                    ->schema([
                        InfoFieldset::make('Instruktur')
                            ->schema([
                                ImageEntry::make('instruktur.foto')
                                    ->label('')
                                    ->circular()
                                    ->size(120),
                                Group::make()
                                    ->schema([
                                        TextEntry::make('instruktur.nama')
                                            ->label('Nama Lengkap')
                                            ->inlineLabel()
                                            ->columnSpanFull(),
                                        TextEntry::make('nama')
                                            ->label('Kelas')
                                            ->inlineLabel()
                                            ->columnSpanFull(),
                                        TextEntry::make('instruktur.pengalaman')
                                            ->label('Keahlian')
                                            ->inlineLabel()
                                            ->columnSpanFull()
                                    ])->columnSpan(2),
                            ])->columns(3)
                            ->columnSpan(1),

                        InfoFieldset::make('Waktu Kelas')
                            ->schema([
                                TextEntry::make('tingkatan')
                                    ->label('Tingkatan Kelas :')
                                    ->badge()
                                    ->color('info')
                                    ->inlineLabel()
                                    ->columnSpanFull(),
                                TextEntry::make('jumlah_pertemuan')
                                    ->label('Pertemuan :')
                                    ->suffix(' x Pertemuan')
                                    ->inlineLabel()
                                    ->columnSpanFull(),
                                TextEntry::make('jam_mulai')
                                    ->label('Waktu Kelas : ')
                                    ->inlineLabel()
                                    ->formatStateUsing(function (Kelas $record) {
                                        $jamMulai = Carbon::createFromFormat('H:i:s', $record->jam_mulai)->format('H:i');
                                        $jamSelesai = Carbon::createFromFormat('H:i:s', $record->jam_selesai)->format('H:i');

                                        return '<div class="text-sm">' . $jamMulai . ' - ' . $jamSelesai . ' WITA</div>';
                                    })
                                    ->html()
                                    ->columnSpanFull(),
                            ])->columns(2)
                            ->columnSpan(1),

                        InfoFieldset::make('Deskripsi')
                            ->schema([
                                TextEntry::make('deskripsi')
                                    ->label('')
                                    ->html()
                                    ->columnSpanFull()
                            ])->columns(1)
                            ->columnSpanFull()
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        return $table
            ->query(
                Kelas::query()->when(
                    $user->hasRole('Peserta'),
                    function ($query) use ($user) {
                        // Filter data berdasarkan kelas user
                        $query->whereHas('kelasUsers', function ($kelasUsersQuery) use ($user) {
                            $kelasUsersQuery->where('user_id', $user->id);
                        });
                    }
                )
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Kelas')
                    ->description(fn(Kelas $record): string => $record->tingkatan)
                    ->searchable(),
                TextColumn::make('instruktur.nama')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('jumlah_pertemuan')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(function (Kelas $record) {
                        $jamMulai = Carbon::createFromFormat('H:i:s', $record->jam_mulai)->format('H:i');
                        $jamSelesai = Carbon::createFromFormat('H:i:s', $record->jam_selesai)->format('H:i');

                        return '<div>' . $record->jumlah_pertemuan . ' kali Pertemuan</div>' .
                            '<div class="text-sm text-gray-500 dark:text-gray-400">' . $jamMulai . ' - ' . $jamSelesai . ' WITA</div>';
                    })
                    ->html(),
                TextColumn::make('harga')
                    ->label('Harga Kelas')
                    ->prefix('Rp ')
                    ->suffix(',00')
                    ->numeric()
                    ->sortable()
                    ->hidden(fn() => !Auth::user()->can('Ubah Kelas')),
            ])
            ->filters([
                SelectFilter::make('instruktur_id')
                    ->label('Instruktur')
                    ->placeholder('Pilih Instruktur')
                    ->relationship('instruktur', 'nama')
                    ->native(false)
                    ->preload()
                    ->searchable()
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
