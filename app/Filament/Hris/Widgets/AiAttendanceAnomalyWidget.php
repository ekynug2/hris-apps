<?php

namespace App\Filament\Hris\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use App\Models\Attendance;
use App\Models\Employee; // Added
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class AiAttendanceAnomalyWidget extends TableWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'AI Anomaly Detection';



    // Since I cannot filter the Query Builder by complex PHP logic easily, 
    // and I cannot install Sushi.
    // I will revert to calculating the IDs of anomalous employees in `mount()` or `boot()` 
    // and then `WhereIn('id', $anomalousIds)`.
    // This is performant enough for < 1000 employees.

    protected array $anomalousIds = [];
    protected array $analysisCache = [];

    public function boot() // Use mount or hydrate? Widgets have lifecycles.
    {
        // Pre-calculate anomalies to filter the query
        // This runs once per request.
        $this->detectAnomalies();
    }

    protected function detectAnomalies()
    {
        // Fetch recent active employees with attendance
        $employees = Employee::whereHas('attendances', function ($q) {
            $q->where('date', '>=', now()->subDays(30));
        })->with([
                    'attendances' => function ($q) {
                        $q->where('date', '>=', now()->subDays(60));
                    }
                ])->get();

        foreach ($employees as $emp) {
            $analysis = $this->analyzeEmployeeData($emp);
            if ($analysis['level']) {
                $this->anomalousIds[] = $emp->id;
                $this->analysisCache[$emp->id] = $analysis;
            }
        }
    }

    public function table(Table $table): Table
    {
        // Ensure detection runs
        if (empty($this->anomalousIds)) {
            // If boot didn't run or no anomalies, this effectively creates an empty query
            $this->detectAnomalies();
        }

        return $table
            ->query(
                Employee::query()->whereIn('id', $this->anomalousIds)
            )
            ->columns([
                TextColumn::make('fullname')
                    ->label('Employee')
                    ->description(fn(Employee $record) => $record->nik)
                    ->formatStateUsing(fn(Employee $record) => $record->first_name . ' ' . $record->last_name)
                    ->searchable(['first_name', 'last_name', 'nik'])
                    ->sortable(['first_name', 'last_name']),

                TextColumn::make('anomaly_title')
                    ->label('Issue')
                    ->state(fn(Employee $record) => $this->analysisCache[$record->id]['title'] ?? 'Unknown'),

                TextColumn::make('anomaly_level')
                    ->label('Severity')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'critical' => 'danger',
                        'warning' => 'warning',
                        'info' => 'info',
                        default => 'gray',
                    })
                    ->state(fn(Employee $record) => $this->analysisCache[$record->id]['level'] ?? 'info'),

                TextColumn::make('description')
                    ->label('AI Insight')
                    ->wrap()
                    ->state(fn(Employee $record) => $this->analysisCache[$record->id]['description'] ?? '-'),

                TextColumn::make('metric')
                    ->label('Data Point')
                    ->state(fn(Employee $record) => $this->analysisCache[$record->id]['metric'] ?? '-'),
            ])
            ->paginated(false);
    }

    protected function analyzeEmployeeData(Employee $employee): array
    {
        $recs = $employee->attendances;
        $last30Days = $recs->filter(fn($r) => Carbon::parse($r->date)->gte(now()->subDays(30)));
        $prev30Days = $recs->filter(fn($r) => Carbon::parse($r->date)->lt(now()->subDays(30)));

        // Priority 1: Resignation Risk
        $badCountCurrent = $last30Days->whereIn('status', ['late', 'absent'])->count();
        $badCountPrev = $prev30Days->whereIn('status', ['late', 'absent'])->count();

        if ($badCountCurrent >= 4) {
            if ($badCountPrev == 0) {
                return [
                    'title' => 'Sudden Behavioral Shift',
                    'level' => 'critical',
                    'description' => "Zero issues in prev period vs {$badCountCurrent} recently.",
                    'metric' => "100% Increase",
                ];
            } elseif ($badCountCurrent >= ($badCountPrev * 2)) {
                $increase = round((($badCountCurrent - $badCountPrev) / $badCountPrev) * 100);
                return [
                    'title' => 'Escalating Issues',
                    'level' => 'critical',
                    'description' => "Absence/Lateness increased widely.",
                    'metric' => "+{$increase}% Trend",
                ];
            }
        }

        // Priority 2: Mon/Fri Pattern
        $lates = $last30Days->where('status', 'late');
        $totalLates = $lates->count();
        if ($totalLates >= 3) {
            $monFriLates = $lates->filter(function ($r) {
                $day = Carbon::parse($r->date)->dayOfWeek;
                return $day === Carbon::MONDAY || $day === Carbon::FRIDAY;
            })->count();

            if (($monFriLates / $totalLates) >= 0.6) {
                return [
                    'title' => 'Mon/Fri Syndrome',
                    'level' => 'warning',
                    'description' => "Late mostly on Mon/Fri.",
                    'metric' => "{$monFriLates}/{$totalLates} Lates",
                ];
            }
        }

        // Priority 3: Burnout
        $longDays = $last30Days->filter(function ($r) {
            if ($r->clock_in && $r->clock_out) {
                $in = Carbon::parse($r->clock_in);
                $out = Carbon::parse($r->clock_out);
                return $out->diffInHours($in) > 10;
            }
            return false;
        });
        if ($longDays->count() >= 5) {
            return [
                'title' => 'Burnout Risk',
                'level' => 'warning',
                'description' => "Working >10h/day frequently.",
                'metric' => "{$longDays->count()} Long Days",
            ];
        }

        return ['level' => null];
    }
}
