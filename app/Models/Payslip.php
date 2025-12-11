<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PayrollItem;
use App\Models\Employee;

/**
 * @property \Illuminate\Support\Carbon|null $period_start
 * @property \Illuminate\Support\Carbon|null $period_end
 * @property \Illuminate\Support\Carbon|null $pay_date
 */
class Payslip extends Model
{
    protected $fillable = [
        'payroll_item_id',
        'employee_id',
        'slip_number',
        'period_start',
        'period_end',
        'pay_date',
        'gross_pay',
        'total_deductions',
        'net_pay',
        'status',
        'is_published',
        'published_at',
        'first_viewed_at',
        'pdf_path',
        'data',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'pay_date' => 'date',
        'gross_pay' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'first_viewed_at' => 'datetime',
        'data' => 'array',
    ];

    // Relationships
    public function payrollItem(): BelongsTo
    {
        return $this->belongsTo(PayrollItem::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    // Helpers
    public static function generateSlipNumber(int $year, int $month): string
    {
        $lastSlip = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastSlip ? (int) substr($lastSlip->slip_number, -4) + 1 : 1;

        return sprintf('SLIP-%04d-%02d-%04d', $year, $month, $sequence);
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function markAsViewed(): void
    {
        if (!$this->first_viewed_at) {
            $this->update([
                'status' => 'viewed',
                'first_viewed_at' => now(),
            ]);
        }
    }

    public function markAsDownloaded(): void
    {
        $this->update(['status' => 'downloaded']);
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->period_start?->format('F Y') ?? '-';
    }

    public function createDataSnapshot(PayrollItem $item): void
    {
        $this->data = [
            'employee' => [
                'nik' => $item->employee->nik,
                'name' => $item->employee->first_name . ' ' . $item->employee->last_name,
                'position' => $item->employee->position->title ?? '-',
                'department' => $item->employee->position->department->name ?? '-',
            ],
            'earnings' => $item->getEarningsBreakdown(),
            'deductions' => $item->getDeductionsBreakdown(),
            'attendance' => [
                'working_days' => $item->working_days,
                'present_days' => $item->present_days,
                'absent_days' => $item->absent_days,
                'late_count' => $item->late_count,
                'overtime_hours' => $item->overtime_hours,
            ],
            'summary' => [
                'gross_pay' => $item->gross_pay,
                'total_deductions' => $item->total_deductions,
                'net_pay' => $item->net_pay,
            ],
        ];
        $this->save();
    }
}
