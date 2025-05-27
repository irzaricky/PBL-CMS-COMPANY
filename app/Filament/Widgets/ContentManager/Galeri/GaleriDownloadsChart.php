<?php

namespace App\Filament\Widgets\ContentManager\Galeri;

use App\Models\Galeri;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class GaleriDownloadsChart extends ApexChartWidget
{
    protected static ?string $heading = 'Most Downloaded Gallery Items';
    protected static ?int $sort = 5;
    protected static bool $deferLoading = true;
    protected string|int|array $columnSpan = 2;
    protected static ?string $pollingInterval = '120s'; // 2 minutes

    protected function getOptions(): array
    {
        $topGaleri = Galeri::query()
            ->orderByDesc('jumlah_unduhan')
            ->limit(10)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Unduhan',
                    'data' => $topGaleri->pluck('jumlah_unduhan')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $topGaleri->pluck('judul_galeri')->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#8b5cf6'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                    'horizontal' => true,
                ],
            ],
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()?->can('widget_GaleriDownloadsChart');
    }
}
