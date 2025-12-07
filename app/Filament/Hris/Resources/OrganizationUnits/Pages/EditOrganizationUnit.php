<?php

namespace App\Filament\Hris\Resources\OrganizationUnits\Pages;

use App\Filament\Hris\Resources\OrganizationUnits\OrganizationUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationUnit extends EditRecord
{
    protected static string $resource = OrganizationUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
