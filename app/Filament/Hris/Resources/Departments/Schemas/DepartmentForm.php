<?php

namespace App\Filament\Hris\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('organization_unit_id')
                    ->relationship('organizationUnit', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('head_id')
                    ->relationship('head', 'first_name') // Using first_name as temporary title since employee name is split
                    ->searchable()
                    ->preload()
                    ->default(null),
                Textarea::make('meta')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
