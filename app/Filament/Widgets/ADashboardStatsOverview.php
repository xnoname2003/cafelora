<?php

namespace App\Filament\Widgets;


use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ADashboardStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $dailyRevenue = Transaction::whereDate('created_at', Carbon::today())->sum('total');

        $monthlyRevenue = Transaction::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total');

        $yearlyRevenue = Transaction::whereYear('created_at', Carbon::now()->year)->sum('total');

        $totalRevenue = Transaction::sum('total');

        $formatRevenue = function (float|int $number): string {
            if ($number < 1000) {
                return Number::currency($number, 'IDR');
            }

            $valueInK = floor($number / 1000);

            return 'Rp ' . number_format($valueInK) . 'K';
        };

        return [
            Stat::make('Penjualan Hari Ini', $formatRevenue($dailyRevenue))
                ->description('Total pendapatan hari ini')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Penjualan Bulan Ini', $formatRevenue($monthlyRevenue))
                ->description('Total pendapatan bulan ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),
            Stat::make('Penjualan Tahun Ini', $formatRevenue($yearlyRevenue))
                ->description('Total pendapatan tahun ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
            Stat::make('Total Semua Penjualan', $formatRevenue($totalRevenue))
                ->description('Total pendapatan keseluruhan')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('primary'),
        ];
    }
}
