<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PayrollRun;
use App\Models\Employee;
use App\Models\Payslip;
use App\Models\PayrollAdjustment;
use App\Models\Attendance;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        // Earnings
        'basic_salary',
        'allowance_position',
        'allowance_transport',
        'allowance_meal',
        'overtime_pay',
        'bonus',
        'other_earnings',
        // Deductions
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'pph21',
        'loan_deduction',
        'late_penalty',
        'absence_deduction',
        'other_deductions',
        // Totals
        'gross_pay',
        'total_deductions',
        'net_pay',
        // Attendance
        'working_days',
        'present_days',
        'absent_days',
        'late_count',
        'overtime_hours',
        // Status
        'is_included',
        'adjustment_reason',
        'components',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowance_position' => 'decimal:2',
        'allowance_transport' => 'decimal:2',
        'allowance_meal' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'other_earnings' => 'decimal:2',
        'bpjs_kesehatan' => 'decimal:2',
        'bpjs_ketenagakerjaan' => 'decimal:2',
        'pph21' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'late_penalty' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'is_included' => 'boolean',
        'components' => 'array',
    ];

    // Relationships
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payslip(): HasOne
    {
        return $this->hasOne(Payslip::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(PayrollAdjustment::class);
    }

    // Calculation helpers
    public function calculateTotals(): void
    {
        // Calculate gross pay (all earnings)
        $this->gross_pay = $this->basic_salary
            + $this->allowance_position
            + $this->allowance_transport
            + $this->allowance_meal
            + $this->overtime_pay
            + $this->bonus
            + $this->other_earnings;

        // Calculate total deductions
        $this->total_deductions = $this->bpjs_kesehatan
            + $this->bpjs_ketenagakerjaan
            + $this->pph21
            + $this->loan_deduction
            + $this->late_penalty
            + $this->absence_deduction
            + $this->other_deductions;

        // Calculate net pay
        $this->net_pay = $this->gross_pay - $this->total_deductions;

        $this->save();
    }

    public function getEarningsBreakdown(): array
    {
        return [
            'Gaji Pokok' => $this->basic_salary,
            'Tunjangan Jabatan' => $this->allowance_position,
            'Tunjangan Transport' => $this->allowance_transport,
            'Tunjangan Makan' => $this->allowance_meal,
            'Lembur' => $this->overtime_pay,
            'Bonus' => $this->bonus,
            'Pendapatan Lain' => $this->other_earnings,
        ];
    }

    public function getDeductionsBreakdown(): array
    {
        return [
            'BPJS Kesehatan' => $this->bpjs_kesehatan,
            'BPJS Ketenagakerjaan' => $this->bpjs_ketenagakerjaan,
            'PPh 21' => $this->pph21,
            'Potongan Pinjaman' => $this->loan_deduction,
            'Denda Keterlambatan' => $this->late_penalty,
            'Potongan Absen' => $this->absence_deduction,
            'Potongan Lain' => $this->other_deductions,
        ];
    }

    public function loadFromEmployee(): void
    {
        $employee = $this->employee;
        $position = $employee->position;

        $this->basic_salary = $position->base_salary ?? 0;
        // Allowances can be set from position meta or custom rules
        $this->allowance_position = 0;
        $this->allowance_transport = 0;
        $this->allowance_meal = 0;
    }

    public function loadFromAttendance(): void
    {
        $payrollRun = $this->payrollRun;
        $employee = $this->employee;

        // Get attendance records for the period
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$payrollRun->period_start, $payrollRun->period_end])
            ->get();

        $this->present_days = $attendances->whereIn('status', ['present', 'late'])->count();
        $this->late_count = $attendances->where('status', 'late')->count();
        $this->absent_days = $attendances->where('status', 'absent')->count();

        // Calculate working days (weekdays in period)
        $start = $payrollRun->period_start->copy();
        $end = $payrollRun->period_end->copy();
        $workingDays = 0;
        while ($start <= $end) {
            if (!$start->isWeekend()) {
                $workingDays++;
            }
            $start->addDay();
        }
        $this->working_days = $workingDays;

        // Calculate penalties (example: Rp 50.000 per late)
        $this->late_penalty = $this->late_count * 50000;
    }
}
