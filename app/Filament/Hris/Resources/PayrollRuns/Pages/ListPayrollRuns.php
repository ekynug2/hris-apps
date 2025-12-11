<?php

namespace App\Filament\Hris\Resources\PayrollRuns\Pages;

use App\Filament\Hris\Resources\PayrollRuns\PayrollRunResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListPayrollRuns extends ListRecords
{
    protected static string $resource = PayrollRunResource::class;

    protected static ?string $title = 'Penggajian';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Payroll Run')
                ->icon('heroicon-o-plus'),
        ];
    }
}
