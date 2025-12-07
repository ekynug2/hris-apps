<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceTrend extends ChartWidget
{
    protected ?string $heading = 'Attendance Trend (Last 7 Days)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $dates = collect();
        $presentCounts = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('Y-m-d');
            $label = $date->format('D, d M');

            $count = Attendance::where('date', $formattedDate)
                ->where('status', 'present')
                ->count();

            $dates->push($label);
            $presentCounts->push($count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Present Employees',
                    'data' => $presentCounts->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)', // Amber
                    'borderColor' => 'rgb(245, 158, 11)',
                ],
            ],
            'labels' => $dates->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
