<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationUnit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'meta',
    ];
}
