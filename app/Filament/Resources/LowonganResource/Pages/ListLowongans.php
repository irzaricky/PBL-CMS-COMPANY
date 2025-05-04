<?php

namespace App\Filament\Resources\LowonganResource\Pages;

use App\Filament\Resources\LowonganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListLowongans extends ListRecords
{
    protected static string $resource = LowonganResource::class;

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
                ->query(fn($query) => $query->orderBy('created_at', 'desc')),
            'Aktif' => Tab::make()
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
