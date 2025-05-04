<?php

namespace App\Filament\Resources\UnduhanResource\Pages;

use App\Filament\Resources\UnduhanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListUnduhans extends ListRecords
{
    protected static string $resource = UnduhanResource::class;

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
                ->query(fn($query) => $query->orderBy('nama_unduhan', 'asc')),
            'Terpopuler' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('jumlah_unduhan', 'desc')),
            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
