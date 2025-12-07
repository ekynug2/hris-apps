<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'date',
        'clock_in',
        'clock_out',
        'status',
        'note',
        'employee_id',
        'lat',
        'lng',
    ];
}
