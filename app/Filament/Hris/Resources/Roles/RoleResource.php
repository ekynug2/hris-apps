<?php

namespace App\Filament\Hris\Resources\Roles;

use App\Filament\Hris\Resources\Roles\Pages\CreateRole;
use App\Filament\Hris\Resources\Roles\Pages\EditRole;
use App\Filament\Hris\Resources\Roles\Pages\ListRoles;
use App\Filament\Hris\Resources\Roles\Schemas\RoleForm;
use App\Filament\Hris\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Pengaturan Sistem";
    protected static ?string $modelLabel = 'Hak Akses';
    protected static ?string $pluralModelLabel = 'Hak Akses';
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
