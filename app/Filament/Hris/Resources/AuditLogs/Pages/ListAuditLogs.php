<?php

namespace App\Filament\Hris\Resources\AuditLogs\Pages;

use App\Filament\Hris\Resources\AuditLogs\AuditLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
