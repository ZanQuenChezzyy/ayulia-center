<?php

namespace App\Filament\Resources\KelasUserResource\Pages;

use App\Filament\Resources\KelasUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelasUser extends EditRecord
{
    protected static string $resource = KelasUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
