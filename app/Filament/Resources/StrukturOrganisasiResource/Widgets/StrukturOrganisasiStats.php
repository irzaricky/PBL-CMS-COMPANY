<?php

namespace App\Filament\Resources\StrukturOrganisasiResource\Widgets;

use App\Models\StrukturOrganisasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Filament\Resources\StrukturOrganisasiResource\Pages\ListStrukturOrganisasis;

class StrukturOrganisasiStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePage(): string
    {
        return ListStrukturOrganisasis::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Jabatan Terisi', StrukturOrganisasi::query()->count())
                ->description('Jumlah total Jabatan yang sedang terisi')
                ->color('primary'),

            Stat::make('Jabatan Aktif Saat Ini', StrukturOrganisasi::query()
                ->whereNull('tanggal_selesai')
                ->orWhere('tanggal_selesai', '>=', now())
                ->count())
                ->description('Jabatan yang masih aktif hingga hari ini')
                ->color('success'),

            Stat::make('Jabatan Kosong / Selesai', StrukturOrganisasi::query()
                ->whereNotNull('tanggal_selesai')
                ->where('tanggal_selesai', '<', now())
                ->count())
                ->description('Jabatan yang sudah selesai atau kosong')
                ->color('danger'),

            Stat::make('Penempatan Baru Bulan Ini', StrukturOrganisasi::query()
                ->whereMonth('tanggal_mulai', now()->month)
                ->whereYear('tanggal_mulai', now()->year)
                ->count())
                ->description('Jumlah orang mulai menjabat bulan ini')
                ->color('info'),
        ];

    }
}
