<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

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
                ->query(fn($query) => $query->orderBy('waktu_start_event', 'asc')),
            'Akan Datang' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('waktu_start_event', '>', now())
                    ->orderBy('waktu_start_event', 'asc')),
            'Sedang Berlangsung' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('waktu_start_event', '<=', now())
                    ->where('waktu_end_event', '>=', now())
                    ->orderBy('waktu_start_event', 'asc')),
            'Selesai' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('waktu_end_event', '<', now())
                    ->orderBy('waktu_start_event', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
