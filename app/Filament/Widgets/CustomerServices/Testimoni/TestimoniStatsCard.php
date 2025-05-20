<?php

namespace App\Filament\Widgets\CustomerServices\Testimoni;

use App\Models\Testimoni;
use App\Enums\ContentStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TestimoniStatsCard extends StatsOverviewWidget
{
    protected ?string $heading = 'Statistik Testimoni';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        return auth()->user()?->can('widget_TestimoniStatsCard');
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Testimoni', Testimoni::query()->count())
                ->description('Jumlah keseluruhan testimoni')
                ->descriptionIcon('heroicon-m-chat-bubble-oval-left-ellipsis')
                ->color('primary'),

            Stat::make('Testimoni Bulan Ini', function () {
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();

                return Testimoni::query()
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count();
            })
                ->description('Total testimoni yang dibuat bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),

            Stat::make('Rating Rata-rata', function () {
                $avgRating = Testimoni::query()
                    ->where('status', ContentStatus::TERPUBLIKASI)
                    ->avg('rating');
                return number_format($avgRating, 1);
            })
                ->description('Rata-rata rating dari semua testimoni')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
