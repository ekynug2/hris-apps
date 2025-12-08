<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BioTemplate extends Model
{
    protected $fillable = [
        'employee_nik',
        'type', // 1=FP, 9=Face
        'no', // Index
        'size',
        'valid',
        'content',
        'version',
        'device_sn',
    ];
}
