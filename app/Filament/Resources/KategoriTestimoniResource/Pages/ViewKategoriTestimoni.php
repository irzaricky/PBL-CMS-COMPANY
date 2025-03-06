<?php

namespace App\Filament\Resources\KategoriTestimoniResource\Pages;

use App\Filament\Resources\KategoriTestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriTestimoni extends ViewRecord
{
    protected static string $resource = KategoriTestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
