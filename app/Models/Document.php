<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'type',
        'file_path',
        'uploaded_at',
        'employee_id',
        'uploaded_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
