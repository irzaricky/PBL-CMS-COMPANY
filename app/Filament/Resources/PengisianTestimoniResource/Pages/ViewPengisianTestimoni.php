<?php

namespace App\Filament\Resources\PengisianTestimoniResource\Pages;

use App\Filament\Resources\PengisianTestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPengisianTestimoni extends ViewRecord
{
    protected static string $resource = PengisianTestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
