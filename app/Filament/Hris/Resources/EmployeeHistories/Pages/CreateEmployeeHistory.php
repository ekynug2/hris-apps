<?php

namespace App\Filament\Hris\Resources\EmployeeHistories\Pages;

use App\Filament\Hris\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployeeHistory extends CreateRecord
{
    protected static string $resource = EmployeeHistoryResource::class;
}
