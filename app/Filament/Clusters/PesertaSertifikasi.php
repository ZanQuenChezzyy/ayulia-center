<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class PesertaSertifikasi extends Cluster
{
    protected static ?string $navigationGroup = 'Instrukutur & Kelas';
    protected static ?string $title = 'Peserta & Sertifikasi';
    protected static ?string $navigationIcon = 'heroicon-o-square-2-stack';
    protected static ?string $activeNavigationIcon = 'heroicon-s-square-2-stack';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'peserta-sertifikasi';
}
