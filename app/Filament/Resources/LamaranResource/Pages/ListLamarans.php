<?php

namespace App\Filament\Resources\LamaranResource\Pages;

use App\Filament\Resources\LamaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLamarans extends ListRecords
{
    protected static string $resource = LamaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
