<?php

namespace App\Filament\Hris\Resources\LeaveRequests\Pages;

use App\Filament\Hris\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
}
