<?php

namespace App\Filament\Resources\ProfilPerusahaanResource\Pages;

use App\Filament\Resources\ProfilPerusahaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfilPerusahaan extends EditRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
