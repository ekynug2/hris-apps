<?php

namespace App\Filament\Hris\Resources\Attendances\Pages;

use App\Filament\Hris\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    /**
     * Override Livewire's queryString to prevent URL persistence.
     * Return empty array to disable all query string syncing.
     */
    protected function queryString(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}