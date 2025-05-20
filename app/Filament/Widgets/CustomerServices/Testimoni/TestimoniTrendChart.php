<?php

namespace App\Filament\Widgets\CustomerServices\Testimoni;

use App\Models\Testimoni;
use App\Enums\ContentStatus;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TestimoniTrendChart extends ApexChartWidget
{
    protected static ?string $heading = 'Trend Testimoni & Rating Kumulatif';
    protected static ?int $sort = 4;
    protected static bool $deferLoading = true;
    protected string|int|array $columnSpan = 2;

    public ?string $filter = 'last_6_months';

    public static function canView(): bool
    {
        return auth()->user()?->can('widget_TestimoniTrendChart');
    }

    protected function getFilters(): ?array
    {
        return [
            'last_30_days' => '30 Hari Terakhir',
            'last_3_months' => '3 Bulan Terakhir',
            'last_6_months' => '6 Bulan Terakhir',
            'last_year' => '1 Tahun Terakhir',
        ];
    }

    protected function getOptions(): array
    {
        $filter = $this->filter ?? 'last_6_months';
        $dates = collect();
        $format = '';

        switch ($filter) {
            case 'last_30_days':
                $format = 'Y-m-d';
                for ($i = 29; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subDays($i)->format($format));
                }
                break;
            case 'last_3_months':
                $format = 'Y-m';
                for ($i = 2; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subMonths($i)->format($format));
                }
                break;
            case 'last_year':
                $format = 'Y-m';
                for ($i = 11; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subMonths($i)->format($format));
                }
                break;
            case 'last_6_months':
            default:
                $format = 'Y-m';
                for ($i = 5; $i >= 0; $i--) {
                    $dates->push(Carbon::now()->subMonths($i)->format($format));
                }
        }

        // Get data for trends
        $totalTestimoniTrend = $this->getTotalTestimoniTrend($dates, $format);
        $avgRatingTrend = $this->getAvgRatingTrend($dates, $format);

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'toolbar' => [
                    'show' => false,
                ],
                'zoom' => [
                    'enabled' => false,
                ],
            ],
            'series' => [
                [
                    'name' => 'Total Testimoni',
                    'data' => $totalTestimoniTrend,
                    'type' => 'column',
                ],
                [
                    'name' => 'Rating Kumulatif',
                    'data' => $avgRatingTrend,
                    'type' => 'line',
                ],
            ],
            'xaxis' => [
                'categories' => $dates->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                [
                    // First y-axis for Total Testimoni
                    'seriesName' => 'Total Testimoni',
                    'title' => [
                        'text' => 'Total Testimoni',
                        'style' => [
                            'color' => '#3b82f6',
                            'fontFamily' => 'inherit',
                        ],
                    ],
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                            'colors' => '#3b82f6',
                        ],
                    ],
                ],
                [                    // Second y-axis for Rating Kumulatif
                    'opposite' => true,
                    'seriesName' => 'Rating Kumulatif',
                    'min' => 0,
                    'max' => 5,
                    'decimalsInFloat' => 1,
                    'title' => [
                        'text' => 'Rating Kumulatif',
                        'style' => [
                            'color' => '#f59e0b',
                            'fontFamily' => 'inherit',
                        ],
                    ],
                    'labels' => [
                        'style' => [
                            'fontFamily' => 'inherit',
                            'colors' => '#f59e0b',
                        ],
                    ],
                ]
            ],
            'colors' => ['#3b82f6', '#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
                'width' => [0, 4],
            ],
            'markers' => [
                'size' => [0, 4],
            ],
        ];
    }

    private function getTotalTestimoniTrend($dates, $format = 'Y-m')
    {
        return $dates->map(function ($date) use ($format) {
            $dateObj = Carbon::createFromFormat($format, $date);

            if ($format === 'Y-m-d') {
                // Daily count
                return Testimoni::whereDate('created_at', $dateObj)->count();
            } else {
                // Monthly count
                return Testimoni::whereYear('created_at', $dateObj->year)
                    ->whereMonth('created_at', $dateObj->month)
                    ->count();
            }
        })->toArray();
    }
    private function getAvgRatingTrend($dates, $format = 'Y-m')
    {
        return $dates->map(function ($date, $index) use ($dates, $format) {
            $dateObj = Carbon::createFromFormat($format, $date);

            // Untuk setiap tanggal, ambil semua testimoni sampai tanggal tersebut
            $query = Testimoni::where('status', ContentStatus::TERPUBLIKASI);

            if ($format === 'Y-m-d') {
                $query->whereDate('created_at', '<=', $dateObj);
            } else {
                // Untuk format bulanan, ambil sampai akhir bulan
                $endOfMonth = $dateObj->copy()->endOfMonth();
                $query->whereDate('created_at', '<=', $endOfMonth);
            }

            // Hitung total rating dan total testimoni
            $totalRating = $query->sum('rating');
            $totalTestimoni = $query->count();

            // Hitung rata-rata kumulatif
            $cumulativeAvg = $totalTestimoni > 0 ? $totalRating / $totalTestimoni : 0;

            return round($cumulativeAvg, 1);
        })->toArray();
    }
}