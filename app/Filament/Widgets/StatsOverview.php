<?php

namespace App\Filament\Widgets;

use App\Models\Instruktur;
use App\Models\Kelas;
use App\Models\KelasUser;
use App\Models\Sertifikat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    private function getChartData(string $modelClass): array
    {
        // Ambil data dari 7 hari terakhir
        return $modelClass::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }
    protected function getStats(): array
    {
        $instrukturData = $this->getChartData(Instruktur::class);
        $kelasData = $this->getChartData(Kelas::class);
        $pesertaData = $this->getChartData(KelasUser::class);
        $sertifikatData = $this->getChartData(Sertifikat::class);

        return [
            // Stat 1: Instruktur Aktif
            Stat::make('Instruktur Ditampilkan', Instruktur::where('di_tampilkan', true)->count())
                ->description('Instruktur yang di tampilkan saat ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart($instrukturData)
                ->color('success'),

            // Stat 2: Kelas Tersedia
            Stat::make('Kelas Tersedia', Kelas::count())
                ->description('Jumlah total kelas yang ditawarkan')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart($kelasData)
                ->color('info'),

            // Stat 3: Peserta Terdaftar
            Stat::make('Peserta Terdaftar', KelasUser::count())
                ->description('Total peserta yang telah mendaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($pesertaData)
                ->color('warning'),

            // Stat 4: Sertifikat Diterbitkan
            Stat::make('Sertifikat Diterbitkan', Sertifikat::count())
                ->description('Jumlah sertifikat yang telah diterbitkan')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart($sertifikatData)
                ->color('primary'),
        ];
    }
    public static function canView(): bool
    {
        return Auth::user()->hasRole('Administrator');
    }
}
