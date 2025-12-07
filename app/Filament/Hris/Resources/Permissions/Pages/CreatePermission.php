<?php

namespace App\Filament\Hris\Resources\Permissions\Pages;

use App\Filament\Hris\Resources\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
