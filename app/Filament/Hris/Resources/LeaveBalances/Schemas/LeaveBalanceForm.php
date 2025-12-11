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
                    ->label('Tahun')
                    ->required()
                    ->numeric(),
                TextInput::make('balance')
                    ->label('Sisa Cuti')
                    ->required()
                    ->numeric(),
                TextInput::make('initial_balance')
                    ->label('Saldo Awal')
                    ->required()
                    ->numeric(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                TextInput::make('leave_type_id')
                    ->label('ID Tipe Cuti')
                    ->required()
                    ->numeric(),
            ]);
    }
}
