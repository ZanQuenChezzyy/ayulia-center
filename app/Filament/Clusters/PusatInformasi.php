<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class PusatInformasi extends Cluster
{
    protected static ?string $navigationGroup = 'Pusat Informasi';
    protected static ?string $title = 'Informasi & Diskusi';
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-information-circle';
    protected static ?int $navigationSort = 6;
    protected static ?string $slug = 'pusat-informasi';
}
