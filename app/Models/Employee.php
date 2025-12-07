<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'is_blacklisted',
        'email',
        'phone',
        'address',
        'position_id',
        'supervisor_id',
        'custom_fields',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
