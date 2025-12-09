<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $table = 'employee_histories';
    protected $fillable = [
        'effective_date',
        'change_type',
        'old_value',
        'new_value',
        'reason',
        'changed_by',
        'employee_id',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
