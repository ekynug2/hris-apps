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
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required(),
            ]);
    }
}
