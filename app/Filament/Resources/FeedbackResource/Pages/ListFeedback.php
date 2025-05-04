<?php

namespace App\Filament\Resources\FeedbackResource\Pages;

use App\Filament\Resources\FeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListFeedback extends ListRecords
{
    protected static string $resource = FeedbackResource::class;

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
            'Belum Ditanggapi' => Tab::make()
                ->query(fn($query) => $query->whereNull('tanggapan_feedback')
                    ->orderBy('created_at', 'desc')),
            'Sudah Ditanggapi' => Tab::make()
                ->query(fn($query) => $query->whereNotNull('tanggapan_feedback')
                    ->orderBy('created_at', 'desc')),
        ];
    }
}
