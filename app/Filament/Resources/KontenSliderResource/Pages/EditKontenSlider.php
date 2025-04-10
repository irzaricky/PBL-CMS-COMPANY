<?php

namespace App\Filament\Resources\KontenSliderResource\Pages;

use App\Filament\Resources\KontenSliderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKontenSlider extends EditRecord
{
    protected static string $resource = KontenSliderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
