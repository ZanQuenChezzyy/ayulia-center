<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\KelasUser;
use App\Models\Sertifikat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PesertaOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Kelas Diikuti', KelasUser::where('user_id', Auth::id())->count())
                ->description('Total kelas yang Anda ikuti')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('Sertifikat Diterbitkan', Sertifikat::whereHas('kelasUser', function ($query) {
                $query->where('user_id', Auth::id());
            })->count())
                ->description('Jumlah sertifikat yang Anda peroleh')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Kelas Berlangsung', KelasUser::where('user_id',  Auth::id())
                ->whereHas('kelas', function ($query) {
                    $query->whereDate('jam_selesai', '>=', now());
                })->count())
                ->description('Kelas yang masih berlangsung')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->hasRole('Peserta');
    }
}
