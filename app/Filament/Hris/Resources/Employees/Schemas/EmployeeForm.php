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
                    ->label('Nama Depan')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Nama Belakang')
                    ->default(null),
                DatePicker::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->required(),
                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options(['male' => 'Laki-laki', 'female' => 'Perempuan'])
                    ->required(),
                DatePicker::make('hire_date')
                    ->label('Tanggal Masuk')
                    ->required(),
                DatePicker::make('resignation_date')
                    ->label('Tanggal Resign'),
                DatePicker::make('termination_date')
                    ->label('Tanggal Berhenti'),
                Select::make('employment_status')
                    ->label('Status Karyawan')
                    ->options([
                        'active' => 'Aktif',
                        'on_probation' => 'Masa Percobaan',
                        'on_notice' => 'Dalam Peringatan (Notice)',
                        'resigned' => 'Resign',
                        'terminated' => 'Diberhentikan',
                    ])
                    ->default('on_probation')
                    ->required(),
                Toggle::make('is_blacklisted')
                    ->label('Blacklist')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->default(null),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->default(null),
                Textarea::make('address')
                    ->label('Alamat')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('position_id')
                    ->label('ID Posisi')
                    ->required()
                    ->numeric(),
                TextInput::make('supervisor_id')
                    ->label('ID Atasan')
                    ->numeric()
                    ->default(null),
                Textarea::make('custom_fields')
                    ->label('Kolom Tambahan (Custom)')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('device_privilege')
                    ->label('Hak Akses Mesin Absensi')
                    ->options([
                        0 => 'User Biasa',
                        2 => 'Pendaftar', // Common ZK value
                        6 => 'Admin Sistem',
                        14 => 'Super Admin',
                    ])
                    ->default(0)
                    ->helperText('Level akses pada mesin fisik absen. 0=User, 14=Admin'),
            ]);
    }
}
