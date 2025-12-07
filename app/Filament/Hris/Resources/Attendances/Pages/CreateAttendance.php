<?php

namespace App\Filament\Hris\Resources\Attendances\Pages;

use App\Filament\Hris\Resources\Attendances\AttendanceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;
}
