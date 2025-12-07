<?php

namespace App\Filament\Hris\Resources\OrganizationUnits;

use App\Filament\Hris\Resources\OrganizationUnits\Pages\CreateOrganizationUnit;
use App\Filament\Hris\Resources\OrganizationUnits\Pages\EditOrganizationUnit;
use App\Filament\Hris\Resources\OrganizationUnits\Pages\ListOrganizationUnits;
use App\Filament\Hris\Resources\OrganizationUnits\Schemas\OrganizationUnitForm;
use App\Filament\Hris\Resources\OrganizationUnits\Tables\OrganizationUnitsTable;
use App\Models\OrganizationUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrganizationUnitResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Organization";
    protected static ?string $model = OrganizationUnit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Schema $schema): Schema
    {
        return OrganizationUnitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationUnitsTable::configure($table);
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
            'index' => ListOrganizationUnits::route('/'),
            'create' => CreateOrganizationUnit::route('/create'),
            'edit' => EditOrganizationUnit::route('/{record}/edit'),
        ];
    }
}
