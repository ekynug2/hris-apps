<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';
    protected $fillable = [
        'start_date',
        'end_date',
        'total_days',
        'status',
        'reason',
        'rejection_note',
        'employee_id',
        'leave_type_id',
        'approved_by',
        'backup_approver_id',
        'attachment',
    ];
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
