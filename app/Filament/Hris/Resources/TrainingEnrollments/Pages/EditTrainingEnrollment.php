<?php

namespace App\Filament\Hris\Resources\TrainingEnrollments\Pages;

use App\Filament\Hris\Resources\TrainingEnrollments\TrainingEnrollmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingEnrollment extends EditRecord
{
    protected static string $resource = TrainingEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
