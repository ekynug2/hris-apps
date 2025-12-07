<?php

namespace App\Filament\Hris\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->required(),
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->default(null),
                DatePicker::make('date_of_birth')
                    ->required(),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                DatePicker::make('hire_date')
                    ->required(),
                DatePicker::make('resignation_date'),
                DatePicker::make('termination_date'),
                Select::make('employment_status')
                    ->options([
            'active' => 'Active',
            'on_probation' => 'On probation',
            'on_notice' => 'On notice',
            'resigned' => 'Resigned',
            'terminated' => 'Terminated',
        ])
                    ->default('on_probation')
                    ->required(),
                Toggle::make('is_blacklisted')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('position_id')
                    ->required()
                    ->numeric(),
                TextInput::make('supervisor_id')
                    ->numeric()
                    ->default(null),
                Textarea::make('custom_fields')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
