<?php

namespace App\Filament\Resources\PendaftaranEventResource\Pages;

use App\Filament\Resources\PendaftaranEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaranEvent extends EditRecord
{
    protected static string $resource = PendaftaranEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
