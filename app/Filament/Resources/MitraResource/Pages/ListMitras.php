<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListMitras extends ListRecords
{
    protected static string $resource = MitraResource::class;

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
                ->query(fn($query) => $query->orderBy('nama', 'asc')),
            'Aktif' => Tab::make()
                ->query(fn($query) => $query->where('status', 'aktif')
                    ->orderBy('tanggal_kemitraan', 'desc')),
            'Nonaktif' => Tab::make()
                ->query(fn($query) => $query->where('status', 'nonaktif')
                    ->orderBy('tanggal_kemitraan', 'desc')),
            'Dokumen Tidak Lengkap' => Tab::make()
                ->query(fn($query) => $query->whereNull('dok_siup')
                    ->whereNull('dok_npwp')
                    ->orderBy('nama', 'asc')),
        ];
    }
}
