<?php

namespace App\Filament\Resources\LowonganResource\Pages;

use App\Enums\ContentStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\LowonganResource;
use App\Filament\Resources\LowonganResource\Widgets\LowonganStats;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListLowongans extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = LowonganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LowonganStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),

            'Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_lowongan', ContentStatus::TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Tidak Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_lowongan', ContentStatus::TIDAK_TERPUBLIKASI->value)
                    ->orderBy('created_at', 'desc')),

            'Periode Dibuka' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('tanggal_dibuka', '<=', now())
                    ->where('tanggal_ditutup', '>=', now())
                    ->orderBy('created_at', 'desc')),

            'Periode Ditutup' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('tanggal_ditutup', '<', now())
                    ->orderBy('tanggal_ditutup', 'desc')),

            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
