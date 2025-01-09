<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrukturResource\Pages;
use App\Filament\Resources\InstrukturResource\RelationManagers;
use App\Models\Instruktur;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;
    protected static ?string $label = 'Instruktur';
    protected static ?string $navigationGroup = 'Instrukutur & Kelas';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $activeNavigationIcon = 'heroicon-s-academic-cap';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total Instruktur';
    protected static ?string $slug = 'instruktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make()
                        ->schema([
                            FileUpload::make('foto')
                                ->label('Foto Instruktur')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '1:1',
                                ])
                                ->imageCropAspectRatio('1:1')
                                ->directory('foto_instruktur')
                                ->visibility('public')
                                ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                                ->columnSpanFull()
                                ->required(),
                        ]),
                ])->columns(1)
                    ->columnSpan(1),

                Tabs::make()
                    ->schema([
                        Tab::make('Informasi Pribadi')
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Instruktur')
                                    ->placeholder('Masukkan nama instruktur')
                                    ->inlineLabel()
                                    ->minLength(3)
                                    ->maxLength(45)
                                    ->required(),

                                TextInput::make('no_telepon')
                                    ->label('Nomor Telepon')
                                    ->placeholder('Masukkan nomor telepon')
                                    ->prefix('+62')
                                    ->inlineLabel()
                                    ->tel()
                                    ->mask(
                                        RawJs::make(<<<'JS'
                                            $input.startsWith('+62')
                                                ? $input.replace(/^\+62/, '')
                                                : ($input.startsWith('62')
                                                    ? $input.replace(/^62/, '')
                                                    : ($input.startsWith('0')
                                                        ? $input.replace(/^0/, '')
                                                        : $input
                                                    )
                                                )
                                        JS)
                                    )
                                    ->stripCharacters([' ', '-', '(', ')'])
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        // Bersihkan prefix dari input
                                        $cleaned = preg_replace('/^(\+62|62|0)/', '', $state);

                                        // Pastikan input dimulai dengan angka 8
                                        if (!str_starts_with($cleaned, '8')) {
                                            $set('no_hp', null); // Atur ke null jika tidak valid
                                        } else {
                                            $set('no_hp', $cleaned); // Simpan nomor bersih tanpa prefix
                                        }
                                    })
                                    ->minValue(1)
                                    ->maxLength(10)
                                    ->maxLength(15)
                                    ->required(),

                                Select::make('pendidikan_terakhir')
                                    ->label('Pendidikan Terakhir')
                                    ->placeholder('Pilih pendidikan terakhir')
                                    ->inlineLabel()
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
                                    ->required(),

                                TextInput::make('pengalaman')
                                    ->label('Pengalaman')
                                    ->placeholder('Masukkan pengalaman instruktur')
                                    ->inlineLabel()
                                    ->minLength(3)
                                    ->maxLength(60)
                                    ->required(),
                            ]),

                        Tab::make('Sertifikat')
                            ->schema([
                                FileUpload::make('sertifikat')
                                    ->label('Sertifikat Instruktur')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '9:16',
                                    ])
                                    ->imageCropAspectRatio('12:17')
                                    ->directory('sertifikat_instruktur')
                                    ->visibility('public')
                                    ->helperText('Format yang didukung: JPG, PNG, atau GIF.')
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpan(3)
                    ->columns(1),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Instruktur')
                    ->formatStateUsing(function (Instruktur $record) {
                        $nameParts = explode(' ', trim($record->nama));
                        $initials = isset($nameParts[1])
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($nameParts[0], 0, 1));
                        $fotoUrl = $record->foto
                            ? asset('storage/' . $record->foto)
                            : 'https://ui-avatars.com/api/?name=' . $initials . '&amp;color=FFFFFF&amp;background=030712';
                        $image = '<img class="w-10 h-10 rounded-lg" style="margin-right: 0.625rem !important;" src="' . $fotoUrl . '" alt="Avatar User">';
                        $nama = '<strong class="text-sm font-medium text-gray-800">' . e($record->nama) . '</strong>';
                        $noTelepon = '<span class="text-sm text-gray-500 dark:text-gray-400">+62 ' . e($record->no_telepon) . '</span>';
                        return '<div class="flex items-center" style="margin-right: 4rem !important">'
                            . $image
                            . '<div>' . $nama . '<br>' . $noTelepon . '</div></div>';
                    })
                    ->html()
                    ->sortable()
                    ->searchable(['nama', 'no_telepon']),

                TextColumn::make('pengalaman')
                    ->searchable(),

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

                ToggleColumn::make('di_tampilkan')
                    ->label('Di tampilkan ?')
                    ->hidden(fn() => !Auth::user()->can('Ubah Instruktur')),

                ImageColumn::make('sertifikat')
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListInstrukturs::route('/'),
            'create' => Pages\CreateInstruktur::route('/create'),
            'view' => Pages\ViewInstruktur::route('/{record}'),
            'edit' => Pages\EditInstruktur::route('/{record}/edit'),
        ];
    }
}
