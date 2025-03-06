<?php

namespace App\Filament\Resources\ProfilPerusahaanResource\Pages;

use App\Filament\Resources\ProfilPerusahaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProfilPerusahaan extends ViewRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
