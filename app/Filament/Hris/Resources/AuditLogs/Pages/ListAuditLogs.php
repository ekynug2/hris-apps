<?php

namespace App\Filament\Hris\Resources\AuditLogs\Pages;

use App\Filament\Hris\Resources\AuditLogs\AuditLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
