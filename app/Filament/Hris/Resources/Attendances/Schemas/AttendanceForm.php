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
                    ->label('Tanggal')
                    ->required(),
                TimePicker::make('clock_in')
                    ->label('Jam Masuk'),
                TimePicker::make('clock_out')
                    ->label('Jam Keluar'),
                Select::make('status')
                    ->label('Status')
                    ->options(['present' => 'Hadir', 'late' => 'Terlambat', 'absent' => 'Absen'])
                    ->required(),
                Textarea::make('note')
                    ->label('Catatan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                TextInput::make('lat')
                    ->label('Latitude')
                    ->default(null),
                TextInput::make('lng')
                    ->label('Longitude')
                    ->default(null),
            ]);
    }
}
