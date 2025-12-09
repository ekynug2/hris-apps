<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'period_start',
        'period_end',
        'basic_salary',
        'allowance_transport',
        'allowance_meal',
        'bpjs_kesehatan',
        'bpjs_ketenagakerjaan',
        'pph21',
        'net_salary',
        'status',
        'rejection_note',
        'employee_id',
        'approved_by',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
    ];
}
