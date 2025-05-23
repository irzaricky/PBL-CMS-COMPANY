<?php

namespace App\Filament\Resources\TestimoniResource\Widgets;

use App\Enums\ContentStatus;
use App\Filament\Resources\TestimoniResource\Pages\ListTestimonis;
use App\Models\Testimoni;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TestimoniStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListTestimonis::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Testimoni', $this->getPageTableQuery()->count())
                ->description('Total testimoni menurut filter')
                ->color('primary'),

            Stat::make('Rating Rata-rata', number_format(Testimoni::query()->avg('rating'), 1) . ' ⭐')
                ->description('Rata-rata penilaian testimoni')
                ->color('warning'),

            Stat::make('Testimoni Bulan Ini', Testimoni::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count())
                ->description('Testimoni yang diterima bulan ini')
                ->color('info'),
        ];
    }
}
