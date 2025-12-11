<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;  // Same row as Device Status

    // Keep original size (1 column)
    protected int|string|array $columnSpan = 1;

    // Make stats display vertically (1 column = stacked)
    protected function getColumns(): int
    {
        return 1;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Karyawan', \App\Models\Employee::where('employment_status', 'active')->count())
                ->description('Karyawan Aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Hadir Hari Ini', \App\Models\Attendance::whereDate('date', now())->where('status', 'present')->count())
                ->description('Sudah Check-in')
                ->descriptionIcon('heroicon-m-check-circle') // Using check-circle-o or solid? Image has solid orange check. Filament uses heroicons.
                ->color('warning'), // Image shows orange/brownish text for description

            Stat::make('Sedang Cuti', \App\Models\LeaveRequest::where('status', 'approved')->where('start_date', '<=', now())->where('end_date', '>=', now())->count())
                ->description('Cuti Disetujui')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
        ];
    }
}
