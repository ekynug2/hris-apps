<?php

namespace App\Filament\Hris\Resources\Payslips\Pages;

use App\Filament\Hris\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\ListRecords;

class ListPayslips extends ListRecords
{
    protected static string $resource = PayslipResource::class;

    protected static ?string $title = 'Slip Gaji';

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
