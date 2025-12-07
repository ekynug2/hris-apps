<?php

namespace App\Filament\Hris\Resources\Attendances\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TimePicker::make('clock_in'),
                TimePicker::make('clock_out'),
                Select::make('status')
                    ->options(['present' => 'Present', 'late' => 'Late', 'absent' => 'Absent'])
                    ->required(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('lat')
                    ->default(null),
                TextInput::make('lng')
                    ->default(null),
            ]);
    }
}
