<?php

namespace App\Filament\Hris\Resources\LeaveRequests;

use App\Filament\Hris\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Hris\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Hris\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Hris\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Hris\Resources\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LeaveRequestResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Absensi & Kehadiran";
    protected static ?string $modelLabel = 'Permintaan Cuti';
    protected static ?string $pluralModelLabel = 'Permintaan Cuti';
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
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
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
            'edit' => EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}
