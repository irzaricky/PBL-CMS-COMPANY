<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use Illuminate\Support\Facades\Storage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RemainingStorageWidget extends ApexChartWidget
{
    protected static ?string $chartId = 'remainingStorage';
    protected static ?string $heading = 'Sisa Storage';
    protected static ?int $sort = 4;
    protected static bool $deferLoading = true;

    protected function getOptions(): array
    {
        $totalSpace = 1024 * 1024 * 1024;
        $usedSpace = $this->calculateUsedStorage();
        $remainingSpace = $totalSpace - $usedSpace;

        // Convert to MB for display
        $usedSpaceMB = round($usedSpace / 1024 / 1024, 2);
        $remainingSpaceMB = round($remainingSpace / 1024 / 1024, 2);

        // Calculate percentage
        $usedPercentage = round(($usedSpace / $totalSpace) * 100, 2);
        $remainingPercentage = 100 - $usedPercentage;

        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'series' => [$usedSpaceMB, $remainingSpaceMB],
            'labels' => ['Terpakai', 'Tersisa'],
            'legend' => [
                'position' => 'bottom',
                'horizontalAlign' => 'center',
            ],
            'colors' => ['#ef4444', '#22c55e'],
            'stroke' => [
                'show' => false,
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'plotOptions' => [
                'pie' => [
                    'donut' => [
                        'size' => '70%',
                        'labels' => [
                            'show' => true,
                            'name' => [
                                'show' => true,
                            ],
                            'value' => [
                                'show' => true,
                            ],
                            'total' => [
                                'show' => true,
                                'label' => 'Total Storage',
                                'formatter' => 'function (w) { return "1 GB"; }',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function calculateUsedStorage(): float
    {
        $totalSize = 0;
        $publicPath = Storage::disk('public')->path('');

        if (is_dir($publicPath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicPath, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $totalSize += $file->getSize();
                }
            }
        }

        return $totalSize;
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            plotOptions: {
                pie: {
                    dataLabels: {
                        formatter: function (val, opts) {
                            return Math.round(val) + '%';
                        }
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val.toFixed(2) + ' MB';
                    }
                }
            }
        }
        JS);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
