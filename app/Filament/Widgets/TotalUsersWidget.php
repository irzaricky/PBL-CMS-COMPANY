<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalUsersWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'aktif')->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();

        return [
            Stat::make('Total user', $totalUsers)
                ->description('Total seluruh user')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('User Aktif', $activeUsers)
                ->description('User dengan status aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('User Baru', $newUsersThisMonth)
                ->description('Bergabung bulan ini')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('super_admin');
    }
}
