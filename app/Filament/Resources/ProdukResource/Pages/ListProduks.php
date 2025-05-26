<?php

namespace App\Filament\Resources\ProdukResource\Pages;

use App\Enums\ContentStatus;
use App\Filament\Resources\ProdukResource\Widgets\ProdukStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProdukResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListProduks extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ProdukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProdukStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('nama_produk', 'asc')),

            'Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_produk', ContentStatus::TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Tidak Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_produk', ContentStatus::TIDAK_TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),

            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
