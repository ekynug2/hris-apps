<?php

namespace App\Filament\Hris\Resources\TrainingPrograms\Pages;

use App\Filament\Hris\Resources\TrainingPrograms\TrainingProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingProgram extends EditRecord
{
    protected static string $resource = TrainingProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
