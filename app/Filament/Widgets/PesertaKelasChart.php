<?php

namespace App\Filament\Widgets;

use App\Models\KelasUser;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PesertaKelasChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Peserta per Kelas';
    protected static ?int $sort = 4;
    protected static ?string $maxHeight = '270px';
    protected function getData(): array
    {
        // Ambil jumlah peserta berdasarkan kelas
        $kelasData = KelasUser::select('kelas_id', DB::raw('count(*) as total'))
            ->groupBy('kelas_id')
            ->with('kelas') // Ambil nama kelas dari relasi
            ->get()
            ->mapWithKeys(function ($kelasUser) {
                return [$kelasUser->kelas->nama => $kelasUser->total];
            });

        $labels = $kelasData->keys()->toArray(); // Nama kelas
        $data = $kelasData->values()->toArray(); // Jumlah peserta

        // Warna RGB untuk setiap kategori dengan opasitas 50%
        $colors = [
            'rgba(51, 87, 255, 0.5)',  // Biru
            'rgba(255, 87, 51, 0.5)',  // Merah
            'rgba(51, 255, 87, 0.5)',  // Hijau
            'rgba(255, 51, 166, 0.5)', // Pink
            'rgba(255, 140, 51, 0.5)', // Oranye
            'rgba(87, 255, 51, 0.5)',  // Hijau terang
            'rgba(255, 51, 255, 0.5)', // Ungu
            'rgba(51, 255, 240, 0.5)', // Biru laut
            'rgba(255, 111, 51, 0.5)', // Oranye gelap
            'rgba(140, 51, 255, 0.5)', // Ungu terang
        ];

        // Jika ada lebih banyak data, tambahkan warna hitam
        $colors = array_merge($colors, array_fill(0, max(0, count($data) - count($colors)), 'rgba(0, 0, 0, 0.5)'));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => 'rgba(0, 0, 0, 0)',
                    'borderWidth' => 1,
                    'borderRadius' => 0,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Administrator');
    }
}
