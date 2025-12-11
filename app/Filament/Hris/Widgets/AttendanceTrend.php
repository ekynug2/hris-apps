<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceTrend extends ChartWidget
{
    protected ?string $heading = 'Tren Kehadiran (7 Hari Terakhir)';

    protected static ?int $sort = 1;  // First row

    protected int|string|array $columnSpan = 1;  // Keep original size

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
                    'label' => 'Karyawan Hadir',
                    'data' => $presentCounts->toArray(),
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.25)', // Soft green
                    'borderColor' => 'rgba(22, 163, 74, 1)',
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
