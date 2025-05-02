<?php

namespace App\Filament\Resources\LowonganResource\Pages;

use App\Filament\Resources\LowonganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLowongan extends EditRecord
{
    protected static string $resource = LowonganResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
