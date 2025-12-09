<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'leave_balances';
    protected $fillable = [
        'year',
        'balance',
        'initial_balance',
        'employee_id',
        'leave_type_id',
    ];
}
