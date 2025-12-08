<?php

namespace App\Filament\Hris\Resources\Employees\Pages;

use App\Filament\Hris\Resources\Employees\EmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
