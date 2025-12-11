<?php

namespace App\Filament\Hris\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
                Select::make('employee_id')
                    ->label('Karyawan Terkait')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Select::make('role_id')
                    ->label('Peran (Role)')
                    ->relationship('role', 'name')
                    ->required(),
            ]);
    }
}
