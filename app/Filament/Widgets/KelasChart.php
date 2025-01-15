<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelasChart extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Kelas Berdasarkan Tingkatan';
    protected static ?int $sort = 3;
    protected function getData(): array
    {
        // Ambil jumlah kelas berdasarkan tingkatan
        $kelasCount = Kelas::select('tingkatan', DB::raw('count(*) as total'))
            ->groupBy('tingkatan')
            ->pluck('total', 'tingkatan');

        $labels = $kelasCount->keys()->toArray();
        $data = $kelasCount->values()->toArray();

        // Warna RGB untuk setiap kategori dengan opasitas 50%
        $colors = [
            'rgba(51, 87, 255, 0.5)',
            'rgba(51, 255, 240, 0.5)',
            'rgba(255, 87, 51, 0.5)',
            'rgba(51, 255, 87, 0.5)',
            'rgba(255, 51, 166, 0.5)',
            'rgba(255, 140, 51, 0.5)',
            'rgba(87, 255, 51, 0.5)',
            'rgba(255, 51, 255, 0.5)',
            'rgba(255, 111, 51, 0.5)',
            'rgba(140, 51, 255, 0.5)',
        ];

        // Jika ada lebih dari 10 data, ulangi warna dengan `array_merge`
        $colors = array_merge($colors, array_fill(0, max(0, count($data) - count($colors)), 'rgba(0, 0, 0, 0.5)'));

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Kelas',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),  // Assign warna untuk setiap batang
                    'borderColor' => 'rgba(0, 0, 0, 0)',  // Border color
                    'borderWidth' => 1,
                    'borderRadius' => 15,
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Administrator');
    }
}
