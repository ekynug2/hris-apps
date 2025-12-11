<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrganizationUnit;
use App\Models\Department;
use App\Models\User;
use App\Models\PayrollItem;
use App\Models\Payslip;

/**
 * @property \Illuminate\Support\Carbon|null $period_start
 * @property \Illuminate\Support\Carbon|null $period_end
 * @property \Illuminate\Support\Carbon|null $pay_date
 */
class PayrollRun extends Model
{
    protected $fillable = [
        'name',
        'period_start',
        'period_end',
        'pay_date',
        'status',
        'organization_unit_id',
        'department_id',
        'total_employees',
        'total_gross',
        'total_deductions',
        'total_net_pay',
        'created_by',
        'finalized_by',
        'finalized_at',
        'settings',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'pay_date' => 'date',
        'finalized_at' => 'datetime',
        'settings' => 'array',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net_pay' => 'decimal:2',
    ];

    // Relationships
    public function organizationUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationUnit::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function payslips(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Payslip::class, PayrollItem::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeFinalized($query)
    {
        return $query->where('status', 'finalized');
    }

    // Helpers
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isFinalized(): bool
    {
        return $this->status === 'finalized';
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'in_progress']);
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->period_start?->format('M Y') ?? '-';
    }

    public function recalculateTotals(): void
    {
        $items = $this->items()->where('is_included', true);

        $this->update([
            'total_employees' => $items->count(),
            'total_gross' => $items->sum('gross_pay'),
            'total_deductions' => $items->sum('total_deductions'),
            'total_net_pay' => $items->sum('net_pay'),
        ]);
    }
}
