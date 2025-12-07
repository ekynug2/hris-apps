<?php

namespace App\Filament\Hris\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('period_start')
                    ->required(),
                DatePicker::make('period_end')
                    ->required(),
                TextInput::make('basic_salary')
                    ->required()
                    ->numeric(),
                TextInput::make('allowance_transport')
                    ->required()
                    ->numeric(),
                TextInput::make('allowance_meal')
                    ->required()
                    ->numeric(),
                TextInput::make('bpjs_kesehatan')
                    ->required()
                    ->numeric(),
                TextInput::make('bpjs_ketenagakerjaan')
                    ->required()
                    ->numeric(),
                TextInput::make('pph21')
                    ->required()
                    ->numeric(),
                TextInput::make('net_salary')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
            'calculated' => 'Calculated',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'rejected' => 'Rejected',
        ])
                    ->required(),
                Textarea::make('rejection_note')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                Textarea::make('details')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
