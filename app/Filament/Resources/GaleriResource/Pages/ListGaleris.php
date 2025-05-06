<?php

namespace App\Filament\Resources\GaleriResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\GaleriResource;
use App\Filament\Resources\GaleriResource\Widgets\GaleriStats;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListGaleris extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = GaleriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GaleriStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua')
                ->query(fn($query) => $query->orderBy('judul_galeri', 'asc')),
            'Aktif' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('judul_galeri', 'asc')),
            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
