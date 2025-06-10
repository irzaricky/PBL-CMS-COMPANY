<?php

namespace App\Filament\Resources\TestimoniResource\Pages;

use App\Enums\ContentStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TestimoniResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListTestimonis extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = TestimoniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
