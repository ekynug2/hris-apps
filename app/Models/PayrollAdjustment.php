<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollAdjustment extends Model
{
    protected $fillable = [
        'payroll_item_id',
        'adjusted_by',
        'component',
        'old_value',
        'new_value',
        'reason',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
    ];

    // Relationships
    public function payrollItem(): BelongsTo
    {
        return $this->belongsTo(PayrollItem::class);
    }

    public function adjuster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    // Helpers
    public function getDifferenceAttribute(): float
    {
        return $this->new_value - $this->old_value;
    }

    public function getFormattedDifferenceAttribute(): string
    {
        $diff = $this->difference;
        $prefix = $diff >= 0 ? '+' : '';
        return $prefix . number_format($diff, 0, ',', '.');
    }
}
