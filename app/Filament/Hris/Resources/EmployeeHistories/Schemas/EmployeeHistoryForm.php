<?php

namespace App\Filament\Hris\Resources\EmployeeHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('effective_date')
                    ->required(),
                Select::make('change_type')
                    ->options([
            'position' => 'Position',
            'department' => 'Department',
            'salary' => 'Salary',
            'status' => 'Status',
        ])
                    ->required(),
                Textarea::make('old_value')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('new_value')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('changed_by')
                    ->required()
                    ->numeric(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
