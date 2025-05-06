<?php

namespace App\Filament\Resources\UnduhanResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\UnduhanResource;
use App\Filament\Resources\UnduhanResource\Widgets\UnduhanStats;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListUnduhans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = UnduhanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UnduhanStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua')
                ->query(fn($query) => $query->orderBy('nama_unduhan', 'asc')),
            'Aktif' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('nama_unduhan', 'asc')),
            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),
            'Terpopuler' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('jumlah_unduhan', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
