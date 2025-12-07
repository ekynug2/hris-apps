<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;

class DeviceStatusPieChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Device Status';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        // Online if activity within last 10 minutes
        $online = \App\Models\Device::where('last_activity', '>=', now()->subMinutes(10))->count();
        $totalDevices = \App\Models\Device::count();
        $offline = max(0, $totalDevices - $online);
        $unauthorized = 0; // Placeholder

        return [
            'datasets' => [
                [
                    'label' => 'Device Status',
                    'data' => [$online, $offline, $unauthorized],
                    'backgroundColor' => [
                        '#4ade80', // Green
                        '#f87171', // Red
                        '#facc15', // Yellow
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Online', 'Offline', 'Unauthorized'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
