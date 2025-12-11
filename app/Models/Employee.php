<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'nik',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'hire_date',
        'resignation_date',
        'termination_date',
        'employment_status',
        'device_privilege',
        'is_blacklisted',
        'photo_path',
        'email',
        'phone',
        'address',
        'position_id',
        'supervisor_id',
        'custom_fields',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'resignation_date' => 'date',
        'termination_date' => 'date',
        'is_blacklisted' => 'boolean',
        'custom_fields' => 'array',
    ];

    // Relationships
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'supervisor_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances(): HasMany
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('employment_status', ['active', 'on_probation']);
    }

    public function scopeInDepartment($query, $departmentId)
    {
        return $query->whereHas('position', fn($q) => $q->where('department_id', $departmentId));
    }
}
