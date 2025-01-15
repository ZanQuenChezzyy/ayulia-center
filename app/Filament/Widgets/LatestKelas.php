<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LatestKelas extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->heading('Kelas Aktif')
            ->query(
                Kelas::query()
                    ->whereHas('kelasUsers', function (Builder $query) {
                        $query->where('user_id', Auth::id());
                    })
                    ->latest('created_at') // Urutkan berdasarkan data terbaru
                    ->take(5) // Batasi jumlah data yang diambil (misalnya 5 kelas terakhir)
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
            ]);
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Peserta');
    }
}
