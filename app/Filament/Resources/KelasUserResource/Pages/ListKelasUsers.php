<?php

namespace App\Filament\Resources\KelasUserResource\Pages;

use App\Filament\Resources\KelasUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelasUsers extends ListRecords
{
    protected static string $resource = KelasUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kelas Peserta'),
        ];
    }
}
