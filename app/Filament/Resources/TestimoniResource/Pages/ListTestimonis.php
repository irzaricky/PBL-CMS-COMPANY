<?php

namespace App\Filament\Resources\TestimoniResource\Pages;

use App\Filament\Resources\TestimoniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListTestimonis extends ListRecords
{
    protected static string $resource = TestimoniResource::class;

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
            'Ditampilkan' => Tab::make()
                ->query(fn($query) => $query->where('status', 'Ditampilkan')
                    ->orderBy('created_at', 'desc')),
            'Tidak Ditampilkan' => Tab::make()
                ->query(fn($query) => $query->where('status', 'Tidak Ditampilkan')
                    ->orderBy('created_at', 'desc')),
            'Rating Tertinggi' => Tab::make()
                ->query(fn($query) => $query->orderBy('rating', 'desc')),
            'Rating Terendah' => Tab::make()
                ->query(fn($query) => $query->orderBy('rating', 'asc')),
        ];
    }
}
