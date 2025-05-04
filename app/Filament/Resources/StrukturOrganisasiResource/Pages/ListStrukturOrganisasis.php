<?php

namespace App\Filament\Resources\StrukturOrganisasiResource\Pages;

use App\Filament\Resources\StrukturOrganisasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListStrukturOrganisasis extends ListRecords
{
    protected static string $resource = StrukturOrganisasiResource::class;

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
                ->query(fn($query) => $query->orderBy('tanggal_mulai', 'desc')),
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
        ];
    }
}
