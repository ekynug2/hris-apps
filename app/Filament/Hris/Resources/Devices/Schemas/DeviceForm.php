<?php

namespace App\Filament\Hris\Resources\Devices\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('alias')
                    ->label('Device Name')
                    ->required(),
                TextInput::make('sn')
                    ->label('Serial Number')
                    ->required(),
                \Filament\Forms\Components\Select::make('department_id')
                    ->label('Area')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // Placeholder for Attendance Device dropdown (not in DB schema yet, maybe options?)
                \Filament\Forms\Components\Select::make('options.attendance_device')
                    ->label('Attendance Device')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ])
                    ->default('Yes'),
                // Placeholder for Request Heartbeat (might be in options or hardcoded for now)
                TextInput::make('options.heartbeat')
                    ->label('Request Heartbeat')
                    ->numeric()
                    ->suffix('Seconds')
                    ->default(10)
                    ->required(),

                // Right Column
                TextInput::make('ip_address')
                    ->label('Device IP'),

                \Filament\Forms\Components\Select::make('terminal_tz')
                    ->label('Time Zone')
                    ->options([
                        8 => 'Etc/GMT+8',
                        7 => 'Etc/GMT+7',
                    ])
                    ->default(8),

                // Placeholder for Registration Device
                \Filament\Forms\Components\Select::make('options.registration_device')
                    ->label('Registration Device')
                    ->options([
                        'Yes' => 'Yes',
                        'No' => 'No',
                    ])
                    ->default('No'),

                // Placeholder for Transfer Mode
                \Filament\Forms\Components\Select::make('options.transfer_mode')
                    ->label('Transfer Mode')
                    ->options([
                        'Real-Time' => 'Real-Time',
                        'Manual' => 'Manual',
                    ])
                    ->default('Real-Time'),
            ]);
    }
}
