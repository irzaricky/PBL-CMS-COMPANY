<?php

namespace App\Filament\Resources\LowonganResource\Widgets;

use App\Filament\Resources\LowonganResource\Pages\ListLowongans;
use App\Models\Lowongan;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class LowonganStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListLowongans::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Lowongan', $this->getPageTableQuery()->count())
                ->description('Total lowongan menurut filter')
                ->color('primary'),

            Stat::make('Lowongan Aktif', Lowongan::query()
                ->where('tanggal_dibuka', '<=', now())
                ->where('tanggal_ditutup', '>=', now())
                ->whereNull('deleted_at')
                ->count())
                ->description('Lowongan yang sedang dibuka')
                ->color('success'),

            Stat::make('Lowongan Bulan Ini', $this->getPageTableQuery()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count())
                ->description('Lowongan yang dibuat bulan ini')
                ->color('warning'),
        ];
    }
}
