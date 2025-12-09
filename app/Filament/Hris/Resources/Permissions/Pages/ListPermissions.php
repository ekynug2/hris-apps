<?php

namespace App\Filament\Hris\Resources\Permissions\Pages;

use App\Filament\Hris\Resources\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
