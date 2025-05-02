<?php

namespace App\Filament\Resources\ProfilPerusahaanResource\Pages;

use App\Filament\Resources\ProfilPerusahaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfilPerusahaan extends EditRecord
{
    protected static string $resource = ProfilPerusahaanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
