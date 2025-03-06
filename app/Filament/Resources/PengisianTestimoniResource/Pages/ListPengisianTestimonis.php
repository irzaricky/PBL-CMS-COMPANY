<?php

namespace App\Filament\Resources\PengisianTestimoniResource\Pages;

use App\Filament\Resources\PengisianTestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengisianTestimonis extends ListRecords
{
    protected static string $resource = PengisianTestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
