<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;

class DepartmentAttendanceBarChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Statistik Kehadiran Departemen';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $departments = \App\Models\Department::withCount([
            'employees as present_count' => function ($query) {
                $query->whereHas('attendances', function ($q) {
                    $q->whereDate('date', now())->whereIn('status', ['present', 'late']);
                });
            }
        ])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Kehadiran',
                    'data' => $departments->pluck('present_count')->toArray(),
                    'backgroundColor' => '#d1d5db', // Grayish color as seen in the image (or similar)
                    'barThickness' => 30,
                ],
            ],
            'labels' => $departments->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
