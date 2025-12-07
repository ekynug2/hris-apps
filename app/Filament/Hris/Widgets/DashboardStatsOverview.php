<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 1; // Default behavior for StatsOverview is usually full width of its container, but if we want it side-by-side with a chart, we rely on the Dashboard Grid.

    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', \App\Models\Employee::where('employment_status', 'active')->count())
                ->description('Active employees')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Present Today', \App\Models\Attendance::whereDate('date', now())->where('status', 'present')->count())
                ->description('Checked in today')
                ->descriptionIcon('heroicon-m-check-circle') // Using check-circle-o or solid? Image has solid orange check. Filament uses heroicons.
                ->color('warning'), // Image shows orange/brownish text for description

            Stat::make('On Leave', \App\Models\LeaveRequest::where('status', 'approved')->where('start_date', '<=', now())->where('end_date', '>=', now())->count())
                ->description('Approved leave')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
        ];
    }
}
