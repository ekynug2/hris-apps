<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;

class AttendancePieChartWidget extends ChartWidget
{
    protected static ?int $sort = 1;  // First row with Attendance Trend

    protected ?string $heading = 'Hadir';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $present = \App\Models\Attendance::whereDate('date', now())->whereIn('status', ['present', 'late'])->count();
        $totalEmployees = \App\Models\Employee::where('employment_status', 'active')->count();
        $absent = max(0, $totalEmployees - $present);

        return [
            'datasets' => [
                [
                    'label' => 'Kehadiran',
                    'data' => [$present, $absent],
                    'backgroundColor' => [
                        '#4ade80', // Green
                        '#f87171', // Red
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Hadir', 'Tidak Hadir'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
