<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    protected $table = 'employee_families';
    protected $fillable = [
        'name',
        'relation',
        'date_of_birth',
        'employee_id',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
