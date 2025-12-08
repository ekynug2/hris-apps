<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'date',
        'clock_in',
        'clock_out',
        'status', // present, absent, etc.
        'photo_path',
        'note',
        'employee_id',
        'lat',
        'lng',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
