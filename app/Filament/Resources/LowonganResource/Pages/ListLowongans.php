<?php

namespace App\Filament\Resources\LowonganResource\Pages;

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
            null => Tab::make('Semua')
                ->query(fn($query) => $query->orderBy('created_at', 'desc')),
            'Dipublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('tanggal_dibuka', '<=', now())
                    ->where('tanggal_ditutup', '>=', now())
                    ->orderBy('created_at', 'desc')),
            'Ditutup' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('tanggal_ditutup', '<', now())
                    ->orderBy('tanggal_ditutup', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
