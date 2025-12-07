<?php

namespace App\Filament\Hris\Resources\Devices\Pages;

use App\Filament\Hris\Resources\Devices\DeviceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}
