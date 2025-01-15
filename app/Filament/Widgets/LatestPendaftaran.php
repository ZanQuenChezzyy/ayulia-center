<?php

namespace App\Filament\Widgets;

use App\Models\Pendaftaran;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class LatestPendaftaran extends BaseWidget
{
    protected static ?string $heading = 'Pendaftar Terbaru';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 5;
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                Pendaftaran::query()
                    ->latest('created_at') // Urutkan berdasarkan tanggal terbaru
                    ->take(10) // Ambil 10 pendaftaran terbaru
            )
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
            ])
            ->defaultSort('created_at', 'desc'); // Urutkan default berdasarkan waktu terbaru
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Administrator');
    }
}
