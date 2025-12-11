<?php

namespace App\Filament\Hris\Resources\LeaveTypes;

use App\Filament\Hris\Resources\LeaveTypes\Pages\CreateLeaveType;
use App\Filament\Hris\Resources\LeaveTypes\Pages\EditLeaveType;
use App\Filament\Hris\Resources\LeaveTypes\Pages\ListLeaveTypes;
use App\Filament\Hris\Resources\LeaveTypes\Schemas\LeaveTypeForm;
use App\Filament\Hris\Resources\LeaveTypes\Tables\LeaveTypesTable;
use App\Models\LeaveType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LeaveTypeResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Absensi & Kehadiran";
    protected static ?string $modelLabel = 'Tipe Cuti';
    protected static ?string $pluralModelLabel = 'Tipe Cuti';
    protected static ?string $model = LeaveType::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    public static function form(Schema $schema): Schema
    {
        return LeaveTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveTypesTable::configure($table);
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
            'index' => ListLeaveTypes::route('/'),
            'create' => CreateLeaveType::route('/create'),
            'edit' => EditLeaveType::route('/{record}/edit'),
        ];
    }
}
