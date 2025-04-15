<?php

namespace App\Filament\Resources\UnduhanResource\Pages;

use App\Filament\Resources\UnduhanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUnduhan extends ViewRecord
{
    protected static string $resource = UnduhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
