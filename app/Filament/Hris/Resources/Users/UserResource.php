<?php

namespace App\Filament\Hris\Resources\Users;

use App\Filament\Hris\Resources\Users\Pages\CreateUser;
use App\Filament\Hris\Resources\Users\Pages\EditUser;
use App\Filament\Hris\Resources\Users\Pages\ListUsers;
use App\Filament\Hris\Resources\Users\Schemas\UserForm;
use App\Filament\Hris\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Pengaturan Sistem";
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
