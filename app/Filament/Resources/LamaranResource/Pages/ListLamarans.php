<?php

namespace App\Filament\Resources\LamaranResource\Pages;

use App\Filament\Resources\LamaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListLamarans extends ListRecords
{
    protected static string $resource = LamaranResource::class;

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
            'Diproses' => Tab::make()
                ->query(fn($query) => $query->where('status_lamaran', 'Diproses')
                    ->orderBy('created_at', 'desc')),
            'Diterima' => Tab::make()
                ->query(fn($query) => $query->where('status_lamaran', 'Diterima')
                    ->orderBy('created_at', 'desc')),
            'Ditolak' => Tab::make()
                ->query(fn($query) => $query->where('status_lamaran', 'Ditolak')
                    ->orderBy('created_at', 'desc')),
        ];
    }
}
