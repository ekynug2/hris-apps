<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_attendance_pie_chart_renders()
    {
        // Setup data
        // ... (Optional, or just test render with empty data)

        \Livewire\Livewire::test(\App\Filament\Hris\Widgets\AttendancePieChartWidget::class)
            ->assertStatus(200);
    }

    public function test_device_status_pie_chart_renders()
    {
        \Livewire\Livewire::test(\App\Filament\Hris\Widgets\DeviceStatusPieChartWidget::class)
            ->assertStatus(200);
    }

    public function test_department_attendance_bar_chart_renders()
    {
        // Need departments to avoid empty chart internal errors if any
        $org = \App\Models\OrganizationUnit::create(['name' => 'HQ', 'type' => 'Branch']);
        \App\Models\Department::create(['name' => 'IT', 'organization_unit_id' => $org->id]);

        \Livewire\Livewire::test(\App\Filament\Hris\Widgets\DepartmentAttendanceBarChartWidget::class)
            ->assertStatus(200);
    }

    public function test_stats_overview_renders()
    {
        \Livewire\Livewire::test(\App\Filament\Hris\Widgets\DashboardStatsOverview::class)
            ->assertStatus(200);
    }
}
