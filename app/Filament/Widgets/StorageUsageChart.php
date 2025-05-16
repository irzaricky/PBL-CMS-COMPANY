<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Filament\Support\RawJs;

class StorageUsageChart extends ApexChartWidget
{
    protected static ?string $chartId = 'storageUsageChart';
    protected static ?string $heading = 'Penggunaan Storage';
    protected static ?int $contentHeight = 300;
    protected static ?int $sort = 2;
    protected static bool $deferLoading = true;
    protected int|string|array $columnSpan = 'full';

    protected function getOptions(): array
    {

        if (!$this->readyToLoad) {
            return [];
        }

        // Get storage usage for the last 30 days
        $storageData = collect(range(29, 0))->map(function ($day) {
            $date = now()->subDays($day)->format('Y-m-d');

            // Get all files from storage/public directory
            $totalSize = 0;
            $publicPath = Storage::disk('public')->path('');

            if (is_dir($publicPath)) {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($publicPath, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        $fileDate = \Carbon\Carbon::createFromTimestamp($file->getMTime())->format('Y-m-d');
                        if ($fileDate === $date) {
                            $totalSize += $file->getSize();
                        }
                    }
                }
            }

            return [
                'date' => $date,
                'size' => round($totalSize / 1024 / 1024, 2) // Convert to MB
            ];
        });

        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'toolbar' => [
                    'show' => true,
                ],
                'zoom' => [
                    'enabled' => true,
                ],
            ],
            'series' => [
                [
                    'name' => 'Storage (MB)',
                    'data' => $storageData->pluck('size')->toArray(),
                ],
            ],
            'xaxis' => [
                'type' => 'datetime',
                'categories' => $storageData->pluck('date')->toArray(),
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
            'colors' => ['#0ea5e9'],
            'stroke' => [
                'curve' => 'smooth',
            ],
            'fill' => [
                'type' => 'gradient',
                'gradient' => [
                    'shade' => 'dark',
                    'type' => 'vertical',
                    'shadeIntensity' => 0.5,
                    'opacityFrom' => 0.7,
                    'opacityTo' => 0.2,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
        {
            xaxis: {
                labels: {
                    formatter: function (val, timestamp) {
                        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
                        return new Date(val).toLocaleDateString('id-ID', options);
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return val.toFixed(2) + ' MB';
                    }
                }
            },
            tooltip: {
                x: {
                    formatter: function (val) {
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        return new Date(val).toLocaleDateString('id-ID', options);
                    }
                },
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
