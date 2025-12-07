<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    protected $fillable = [
        'device_sn',
        'content',
        'trans_time',
        'return_value',
    ];
}
