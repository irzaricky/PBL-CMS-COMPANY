<?php

namespace App\Filament\Resources\UnduhanResource\Widgets;

use App\Models\Unduhan;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Resources\UnduhanResource\Pages\ListUnduhans;

class UnduhanStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListUnduhans::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Unduhan', $this->getPageTableQuery()->count())
                ->description('Total File Unduhan menurut filter')
                ->color('primary'),

            Stat::make('Total Download', Number::format($this->getPageTableQuery()->sum('jumlah_unduhan')))
                ->description('Total file yang diunduh menurut filter')
                ->color('success'),

            Stat::make('Rata-rata Download', Number::format((float) $this->getPageTableQuery()->avg('jumlah_unduhan'), 0))
                ->description('Rata-rata download menurut filter')
                ->color('warning'),

            Stat::make('Unduhan Bulan Ini', Unduhan::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count())
                ->description('File yang ditambahkan bulan ini')
                ->color('info'),
        ];
    }
}
