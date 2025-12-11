<?php

namespace App\Filament\Hris\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Departemen')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('organization_unit_id')
                    ->label('Unit Organisasi')
                    ->relationship('organizationUnit', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('head_id')
                    ->label('Kepala Departemen')
                    ->relationship('head', 'first_name') // Using first_name as temporary title since employee name is split
                    ->searchable()
                    ->preload()
                    ->default(null),
                Textarea::make('meta')
                    ->label('Meta Data')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
