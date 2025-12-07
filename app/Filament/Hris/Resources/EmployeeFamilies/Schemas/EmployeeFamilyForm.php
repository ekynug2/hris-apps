<?php

namespace App\Filament\Hris\Resources\EmployeeFamilies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeFamilyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('relation')
                    ->options(['spouse' => 'Spouse', 'child' => 'Child', 'parent' => 'Parent'])
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->required(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
