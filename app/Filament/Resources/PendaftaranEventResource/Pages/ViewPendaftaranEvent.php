<?php

namespace App\Filament\Resources\PendaftaranEventResource\Pages;

use App\Filament\Resources\PendaftaranEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPendaftaranEvent extends ViewRecord
{
    protected static string $resource = PendaftaranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
