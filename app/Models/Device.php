<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'sn',
        'ip_address',
        'alias',
        'terminal_name',
        'fw_ver',
        'state',
        'terminal_tz',
        'push_version',
        'dev_language',
        'last_activity',
        'options',
        'department_id',
        'user_count',
        'fp_count',
        'face_count',
        'palm_count',
        'transaction_count',
        'cmd_count',
    ];

    public const STATUS_ONLINE = 'online';
    public const STATUS_OFFLINE = 'offline';
    public const STATUS_UNAUTHORIZED = 'unauthorized';

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getComputedStatusAttribute(): string
    {
        if (is_null($this->department_id)) {
            return self::STATUS_UNAUTHORIZED;
        }

        if ($this->last_activity >= now()->subMinutes(10)) {
            return self::STATUS_ONLINE;
        }

        return self::STATUS_OFFLINE;
    }
}
