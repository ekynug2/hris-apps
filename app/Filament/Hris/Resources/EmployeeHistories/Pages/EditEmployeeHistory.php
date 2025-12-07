<?php

namespace App\Filament\Hris\Resources\EmployeeHistories\Pages;

use App\Filament\Hris\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployeeHistory extends EditRecord
{
    protected static string $resource = EmployeeHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
