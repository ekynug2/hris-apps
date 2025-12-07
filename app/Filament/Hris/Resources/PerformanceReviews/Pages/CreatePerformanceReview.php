<?php

namespace App\Filament\Hris\Resources\PerformanceReviews\Pages;

use App\Filament\Hris\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePerformanceReview extends CreateRecord
{
    protected static string $resource = PerformanceReviewResource::class;
}
