<?php

namespace App\Filament\Hris\Widgets;

use Filament\Widgets\Widget;

class AiAttendanceInsightsWidget extends Widget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.hris.widgets.ai-attendance-insights-widget';

    public array $insights = [];

    public function mount()
    {
        $this->generateInsights();
    }

    protected function generateInsights()
    {
        // 1. Analyze Late Arrivals (Last 7 days)
        // Assume 'late' status is set, or check clock_in > 09:00 (Example logic)
        $lateThreshold = '09:00:00';
        $lateCount = \App\Models\Attendance::where('date', '>=', now()->subDays(7))
            ->where(function ($q) use ($lateThreshold) {
                $q->where('status', 'late')
                    ->orWhere('clock_in', '>', $lateThreshold);
            })->count();

        // 2. Analyze Burnout Risk (Working hours > 10 hours)
        // Simple heuristic: ClockOut - ClockIn > 10 hours
        $burnoutCount = \App\Models\Attendance::where('date', '>=', now()->subDays(7))
            ->whereNotNull('clock_in')
            ->whereNotNull('clock_out')
            ->whereRaw('TIMEDIFF(clock_out, clock_in) > "10:00:00"')
            ->count();

        // 3. Department with lowest attendance
        // Group by department.. complicated query, let's keep it simple for V1.

        $analysis = [];

        if ($lateCount > 5) {
            $analysis[] = [
                'type' => 'warning',
                'title' => 'High Lateness Rate',
                'message' => "AI detected {$lateCount} late arrivals in the last 7 days. Consider reviewing morning shift schedules.",
                'icon' => 'heroicon-o-clock',
            ];
        } else {
            $analysis[] = [
                'type' => 'success',
                'title' => 'Punctuality is Good',
                'message' => "Only {$lateCount} late arrivals detected this week. Keep up the good work!",
                'icon' => 'heroicon-o-check-badge',
            ];
        }

        if ($burnoutCount > 3) {
            $analysis[] = [
                'type' => 'danger',
                'title' => 'Potential Burnout Risk',
                'message' => "Found {$burnoutCount} instances of employees working over 10 hours daily. Monitor team workload to prevent fatigue.",
                'icon' => 'heroicon-o-fire',
            ];
        }

        // Always add a "Normal" state if nothing critical
        if (empty($analysis)) {
            $analysis[] = [
                'type' => 'info',
                'title' => 'Stable Operations',
                'message' => "AI Analysis shows no significant anomalies in attendance patterns today.",
                'icon' => 'heroicon-o-sparkles',
            ];
        }

        $this->insights = $analysis;
    }
}
