<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\ChartWidget;

class DeviceStatusPieChartWidget extends ChartWidget
{
    protected static ?int $sort = 1;  // First widget (left side)

    protected ?string $heading = 'Status Perangkat';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        // 1. Unauthorized: Department is NULL
        $unauthorized = \App\Models\Device::whereNull('department_id')->count();

        // 2. Authorized Devices
        $authorizedDevices = \App\Models\Device::whereNotNull('department_id');

        // 3. Online: Authorized AND active in last 10 mins
        $online = (clone $authorizedDevices)
            ->where('last_activity', '>=', now()->subMinutes(10))
            ->count();

        // 4. Offline: Authorized BUT inactive
        $offline = (clone $authorizedDevices)
            ->where('last_activity', '<', now()->subMinutes(10)) // Explicitly check inactive
            ->count();
        // Alternatively: $offline = $authorizedDevices->count() - $online;

        return [
            'datasets' => [
                [
                    'label' => 'Status Perangkat',
                    'data' => [$online, $offline, $unauthorized],
                    'backgroundColor' => [
                        '#4ade80', // Green
                        '#f87171', // Red
                        '#facc15', // Yellow
                    ],
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Online', 'Offline', 'Tidak Terdaftar'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
