<?php

namespace App\Filament\Resources\MediaSosialResource\Pages;

use App\Filament\Resources\MediaSosialResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMediaSosial extends ViewRecord
{
    protected static string $resource = MediaSosialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
