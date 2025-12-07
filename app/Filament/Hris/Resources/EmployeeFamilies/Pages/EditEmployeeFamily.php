<?php

namespace App\Filament\Hris\Resources\EmployeeFamilies\Pages;

use App\Filament\Hris\Resources\EmployeeFamilies\EmployeeFamilyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeFamily extends EditRecord
{
    protected static string $resource = EmployeeFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
