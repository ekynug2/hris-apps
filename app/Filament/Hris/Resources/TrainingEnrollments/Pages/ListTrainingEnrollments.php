<?php

namespace App\Filament\Hris\Resources\TrainingEnrollments\Pages;

use App\Filament\Hris\Resources\TrainingEnrollments\TrainingEnrollmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrainingEnrollments extends ListRecords
{
    protected static string $resource = TrainingEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
