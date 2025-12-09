<?php

namespace App\Filament\Hris\Resources\EmployeeHistories\Pages;

use App\Filament\Hris\Resources\EmployeeHistories\EmployeeHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeHistories extends ListRecords
{
    protected static string $resource = EmployeeHistoryResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
