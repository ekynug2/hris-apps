<?php

namespace App\Filament\Hris\Resources\LeaveTypes\Pages;

use App\Filament\Hris\Resources\LeaveTypes\LeaveTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveType extends EditRecord
{
    protected static string $resource = LeaveTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
