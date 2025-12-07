<?php

namespace App\Filament\Hris\Resources\Payrolls\Pages;

use App\Filament\Hris\Resources\Payrolls\PayrollResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePayroll extends CreateRecord
{
    protected static string $resource = PayrollResource::class;
}
