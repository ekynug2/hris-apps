<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $table = 'performance_reviews';
    protected $fillable = [
        'review_period',
        'rating',
        'comments',
        'employee_id',
        'reviewer_id',
        'kpi_scores',
    ];
}
