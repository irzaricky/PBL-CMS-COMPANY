<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use App\Filament\Resources\GaleriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListGaleris extends ListRecords
{
    protected static string $resource = GaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua')
                ->query(fn($query) => $query->orderBy('judul_galeri', 'asc')),
            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
