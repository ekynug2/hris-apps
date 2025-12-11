<?php

namespace App\Filament\Hris\Resources\LeaveBalances;

use App\Filament\Hris\Resources\LeaveBalances\Pages\CreateLeaveBalance;
use App\Filament\Hris\Resources\LeaveBalances\Pages\EditLeaveBalance;
use App\Filament\Hris\Resources\LeaveBalances\Pages\ListLeaveBalances;
use App\Filament\Hris\Resources\LeaveBalances\Schemas\LeaveBalanceForm;
use App\Filament\Hris\Resources\LeaveBalances\Tables\LeaveBalancesTable;
use App\Models\LeaveBalance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LeaveBalanceResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Absensi & Kehadiran";
    protected static ?string $modelLabel = 'Saldo Cuti';
    protected static ?string $pluralModelLabel = 'Saldo Cuti';
    protected static ?string $model = LeaveBalance::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    public static function form(Schema $schema): Schema
    {
        return LeaveBalanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveBalancesTable::configure($table);
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
            'index' => ListLeaveBalances::route('/'),
            'create' => CreateLeaveBalance::route('/create'),
            'edit' => EditLeaveBalance::route('/{record}/edit'),
        ];
    }
}
