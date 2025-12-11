<?php

namespace App\Filament\Hris\Resources\EmployeeHistories;

use App\Filament\Hris\Resources\EmployeeHistories\Pages\CreateEmployeeHistory;
use App\Filament\Hris\Resources\EmployeeHistories\Pages\EditEmployeeHistory;
use App\Filament\Hris\Resources\EmployeeHistories\Pages\ListEmployeeHistories;
use App\Filament\Hris\Resources\EmployeeHistories\Schemas\EmployeeHistoryForm;
use App\Filament\Hris\Resources\EmployeeHistories\Tables\EmployeeHistoriesTable;
use App\Models\EmployeeHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeHistoryResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Manajemen SDM";
    protected static ?string $modelLabel = 'Riwayat Karyawan';
    protected static ?string $pluralModelLabel = 'Riwayat Karyawan';
    protected static ?string $model = EmployeeHistory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public static function form(Schema $schema): Schema
    {
        return EmployeeHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeHistoriesTable::configure($table);
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
            'index' => ListEmployeeHistories::route('/'),
            'create' => CreateEmployeeHistory::route('/create'),
            'edit' => EditEmployeeHistory::route('/{record}/edit'),
        ];
    }
}
