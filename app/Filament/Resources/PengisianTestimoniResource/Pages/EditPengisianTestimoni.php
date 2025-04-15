<?php

namespace App\Filament\Resources\PengisianTestimoniResource\Pages;

use App\Filament\Resources\PengisianTestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengisianTestimoni extends EditRecord
{
    protected static string $resource = PengisianTestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
