<?php

namespace App\Filament\Hris\Resources\DeviceCommands\Pages;

use App\Filament\Hris\Resources\DeviceCommands\DeviceCommandResource;
use Filament\Resources\Pages\ListRecords;

class ListDeviceCommands extends ListRecords
{
    protected static string $resource = DeviceCommandResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
