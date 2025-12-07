<?php

namespace App\Filament\Hris\Resources\PerformanceReviews\Pages;

use App\Filament\Hris\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceReview extends EditRecord
{
    protected static string $resource = PerformanceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
