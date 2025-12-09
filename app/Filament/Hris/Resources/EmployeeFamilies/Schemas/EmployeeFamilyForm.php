<?php

namespace App\Filament\Hris\Resources\EmployeeFamilies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class EmployeeFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('name')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Select::make('relation')
                    ->options(['spouse' => 'Spouse', 'child' => 'Child', 'parent' => 'Parent'])
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->required(),
            ]);
    }
}
