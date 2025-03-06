<?php

namespace App\Filament\Resources\KategoriUnduhanResource\Pages;

use App\Filament\Resources\KategoriUnduhanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKategoriUnduhan extends ViewRecord
{
    protected static string $resource = KategoriUnduhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
