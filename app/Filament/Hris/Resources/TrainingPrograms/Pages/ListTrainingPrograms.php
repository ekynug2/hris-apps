<?php

namespace App\Filament\Hris\Resources\TrainingPrograms\Pages;

use App\Filament\Hris\Resources\TrainingPrograms\TrainingProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrainingPrograms extends ListRecords
{
    protected static string $resource = TrainingProgramResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
