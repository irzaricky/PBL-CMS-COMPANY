<?php

namespace App\Filament\Resources\StrukturOrganisasiResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\StrukturOrganisasiResource;
use App\Filament\Resources\StrukturOrganisasiResource\Widgets\StrukturOrganisasiStats;

class ListStrukturOrganisasis extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = StrukturOrganisasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StrukturOrganisasiStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('tanggal_mulai', 'desc')),
            'Posisi Aktif' => Tab::make()
                ->query(fn($query) => $query->whereHas('user', fn($query) => $query->where('status', 'aktif'))
                    ->where(function ($query) {
                        $query->whereNull('tanggal_selesai')
                            ->orWhere('tanggal_selesai', '>=', now());
                    })
                    ->orderBy('tanggal_mulai', 'desc')),
            'Posisi Nonaktif' => Tab::make()
                ->query(fn($query) => $query->where(function ($query) {
                    $query->whereHas('user', fn($query) => $query->where('status', 'nonaktif'))
                        ->orWhere('tanggal_selesai', '<', now());
                })
                    ->orderBy('tanggal_selesai', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
