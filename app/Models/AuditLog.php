<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'event_time',
        'event_type',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'user_id',
        'properties',
        'is_from_device',
        'device_sn',
    ];

    protected $casts = [
        'properties' => 'array',
        'event_time' => 'datetime',
        'is_from_device' => 'boolean',
    ];
}
