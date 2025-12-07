<?php

namespace App\Filament\Hris\Resources\EmployeeFamilies\Pages;

use App\Filament\Hris\Resources\EmployeeFamilies\EmployeeFamilyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeFamilies extends ListRecords
{
    protected static string $resource = EmployeeFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
