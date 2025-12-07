<?php

namespace App\Filament\Hris\Resources\LeaveBalances\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaveBalanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->required()
                    ->numeric(),
                TextInput::make('initial_balance')
                    ->required()
                    ->numeric(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('leave_type_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
