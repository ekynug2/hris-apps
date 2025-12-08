<?php

namespace App\Filament\Hris\Resources\Employees\Pages;

use App\Filament\Hris\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}

