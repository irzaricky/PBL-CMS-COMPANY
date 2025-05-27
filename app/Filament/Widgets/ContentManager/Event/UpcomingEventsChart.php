<?php

namespace App\Filament\Widgets\ContentManager\Event;

use App\Models\Event;
use Filament\Support\RawJs;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UpcomingEventsChart extends ApexChartWidget
{
    protected static ?string $heading = 'Event yang akan datang';
    protected static ?int $sort = 4;
    protected static bool $deferLoading = true;
    protected string|int|array $columnSpan = 1;
    protected static ?string $pollingInterval = '300s'; // 5 minutes
    protected function getOptions(): array
    {
        $upcomingEvents = Event::query()
            ->where('waktu_start_event', '>', now())
            ->orderByDesc('jumlah_pendaftar')
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
                    'name' => 'Pendaftar',
                    'data' => $upcomingEvents->pluck('jumlah_pendaftar')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $upcomingEvents->pluck('nama_event')->toArray(),
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
            'colors' => ['#f43f5e'],
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
        return auth()->user()?->can('widget_UpcomingEventsChart');
    }
}
