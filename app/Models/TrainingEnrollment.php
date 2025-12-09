<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    protected $fillable = [
        'status',
        'certificate_url',
        'employee_id',
        'training_id',
    ];
}
