<?php

namespace App\Filament\Resources\CaseStudyResource\Pages;

use App\Filament\Resources\CaseStudyResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\CaseStudyResource\Widgets\CaseStudyStats;

class ListCaseStudies extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = CaseStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            CaseStudyStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua')
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('judul_case_study', 'asc')),
            'Terpublikasi' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_case_study', 'published')
                    ->orderBy('judul_case_study', 'asc')),
            'Draft' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->where('status_case_study', 'draft')
                    ->orderBy('judul_case_study', 'asc')),
            'Terbaru' => Tab::make()
                ->query(fn($query) => $query->whereNull('deleted_at')
                    ->orderBy('created_at', 'desc')),
            'Diarsipkan' => Tab::make()
                ->query(fn($query) => $query->onlyTrashed()),
        ];
    }
}
