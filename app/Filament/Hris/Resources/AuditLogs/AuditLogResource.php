<?php

namespace App\Filament\Hris\Resources\AuditLogs;

use App\Filament\Hris\Resources\AuditLogs\Pages\CreateAuditLog;
use App\Filament\Hris\Resources\AuditLogs\Pages\EditAuditLog;
use App\Filament\Hris\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Filament\Hris\Resources\AuditLogs\Schemas\AuditLogForm;
use App\Filament\Hris\Resources\AuditLogs\Tables\AuditLogsTable;
use App\Models\AuditLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "System Settings";
    protected static ?string $model = AuditLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Schema $schema): Schema
    {
        return AuditLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditLogs::route('/'),
        ];
    }
}
