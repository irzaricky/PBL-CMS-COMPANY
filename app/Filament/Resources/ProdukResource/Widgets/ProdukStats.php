<?php

namespace App\Filament\Resources\ProdukResource\Widgets;

use App\Filament\Resources\ProdukResource\Pages\ListProduks;
use App\Models\Produk;
use Flowframe\Trend\Trend;
use Illuminate\Support\Number;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProdukStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListProduks::class;
    }

    protected function getStats(): array
    {

        return [
            Stat::make('Total Produk', $this->getPageTableQuery()->count())
                ->description('Total produk menurut filter')
                ->color('primary'),

            Stat::make('Produk Aktif', Produk::query()->whereNull('deleted_at')->count())
                ->description('Produk yang tersedia')
                ->color('success'),

            Stat::make('Produk Diarsipkan', Produk::query()->withTrashed()->whereNotNull('deleted_at')->count())
                ->description('Produk yang diarsipkan')
                ->color('warning'),

            Stat::make('Produk Bulan Ini', Produk::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count())
                ->description('Produk yang ditambahkan bulan ini')
                ->color('info'),
        ];
    }
}
