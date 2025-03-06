<?php

namespace App\Filament\Resources\KategoriTestimoniResource\Pages;

use App\Filament\Resources\KategoriTestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriTestimonis extends ListRecords
{
    protected static string $resource = KategoriTestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
