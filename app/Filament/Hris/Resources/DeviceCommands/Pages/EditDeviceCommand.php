<?php

namespace App\Filament\Hris\Resources\DeviceCommands\Pages;

use App\Filament\Hris\Resources\DeviceCommands\DeviceCommandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeviceCommand extends EditRecord
{
    protected static string $resource = DeviceCommandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
