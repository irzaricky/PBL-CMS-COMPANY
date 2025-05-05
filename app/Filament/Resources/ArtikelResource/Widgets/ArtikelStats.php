<?php

namespace App\Filament\Resources\ArtikelResource\Widgets;

use App\Filament\Resources\ArtikelResource\Pages\ListArtikels;
use App\Models\Artikel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class ArtikelStats extends BaseWidget
{
    use InteractsWithPageTable;

    // Set polling interval to 5 seconds
    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListArtikels::class;
    }

    protected function getStats(): array
    {
        // Get trend data for the past year
        $artikelData = Trend::model(Artikel::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Total Artikel', $this->getPageTableQuery()->count())
                ->chart(
                    $artikelData
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                )
                ->color('primary'),
            Stat::make('Total View', number_format($this->getPageTableQuery()->sum('jumlah_view'), 0, ',', '.'))
                ->description('Total view semua artikel')
                ->color('success'),
            Stat::make('Rata-rata View', number_format($this->getPageTableQuery()->avg('jumlah_view'), 0))
                ->description('Rata-rata view per artikel')
                ->color('warning'),
            Stat::make('Jumlah Artikel Bulan Ini', Artikel::query()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count())
                ->description('Artikel yang dibuat bulan ini')
                ->color('danger'),
        ];
    }
}
