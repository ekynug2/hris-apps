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
                    ->label('Nama')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Select::make('relation')
                    ->label('Hubungan')
                    ->options(['spouse' => 'Pasangan', 'child' => 'Anak', 'parent' => 'Orang Tua'])
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->required(),
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'first_name')
                    ->required(),
            ]);
    }
}
