<?php

namespace App\Filament\Hris\Resources\Devices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('alias')
                    ->label('Nama Perangkat')
                    ->required(),
                TextInput::make('sn')
                    ->label('Nomor Seri')
                    ->required(),
                Select::make('department_id')
                    ->label('Area / Departemen')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                // Placeholder for Attendance Device dropdown (not in DB schema yet, maybe options?)
                Select::make('options.attendance_device')
                    ->label('Perangkat Absensi')
                    ->options([
                        'Yes' => 'Ya',
                        'No' => 'Tidak',
                    ])
                    ->default('Yes'),
                // Placeholder for Request Heartbeat (might be in options or hardcoded for now)
                TextInput::make('options.heartbeat')
                    ->label('Request Heartbeat')
                    ->numeric()
                    ->suffix('Detik')
                    ->default(10)
                    ->required(),

                // Right Column
                TextInput::make('ip_address')
                    ->label('Alamat IP Perangkat'),

                Select::make('terminal_tz')
                    ->label('Zona Waktu')
                    ->options([
                        8 => 'Etc/GMT+8 (WITA)',
                        7 => 'Etc/GMT+7 (WIB)',
                    ])
                    ->default(8),

                // Placeholder for Registration Device
                Select::make('options.registration_device')
                    ->label('Perangkat Pendaftaran')
                    ->options([
                        'Yes' => 'Ya',
                        'No' => 'Tidak',
                    ])
                    ->default('No'),

                // Placeholder for Transfer Mode
                Select::make('options.transfer_mode')
                    ->label('Mode Transfer')
                    ->options([
                        'Real-Time' => 'Real-Time',
                        'Manual' => 'Manual',
                    ])
                    ->default('Real-Time'),
            ]);
    }
}
