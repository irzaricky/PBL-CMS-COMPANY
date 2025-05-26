<?php

namespace App\Filament\Resources\ArtikelResource\Pages;

use Filament\Actions;
use App\Enums\ContentStatus;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ArtikelResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\ArtikelResource\Widgets\ArtikelStats;

class ListArtikels extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ArtikelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ArtikelStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('judul_artikel', 'asc')),

            'Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_artikel', ContentStatus::TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Tidak Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_artikel', ContentStatus::TIDAK_TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),

            'Terlama' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'asc')),

            'Terpopuler' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('jumlah_view', 'desc')),

            'Trending' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('jumlah_view', 'desc')
                    ->where('created_at', '>=', now()->subDays(30))),

            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
