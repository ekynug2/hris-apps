<?php

namespace App\Filament\Hris\Resources\EmployeeFamilies;

use App\Filament\Hris\Resources\EmployeeFamilies\Pages\CreateEmployeeFamily;
use App\Filament\Hris\Resources\EmployeeFamilies\Pages\EditEmployeeFamily;
use App\Filament\Hris\Resources\EmployeeFamilies\Pages\ListEmployeeFamilies;
use App\Filament\Hris\Resources\EmployeeFamilies\Schemas\EmployeeFamilyForm;
use App\Filament\Hris\Resources\EmployeeFamilies\Tables\EmployeeFamiliesTable;
use App\Models\EmployeeFamily;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeFamilyResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "HR Management";
    protected static ?string $model = EmployeeFamily::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function form(Schema $schema): Schema
    {
        return EmployeeFamilyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeFamiliesTable::configure($table);
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
            'index' => ListEmployeeFamilies::route('/'),
            'create' => CreateEmployeeFamily::route('/create'),
            'edit' => EditEmployeeFamily::route('/{record}/edit'),
        ];
    }
}
