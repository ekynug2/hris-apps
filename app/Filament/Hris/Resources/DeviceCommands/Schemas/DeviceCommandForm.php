<?php

namespace App\Filament\Hris\Resources\DeviceCommands\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DeviceCommandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('device_sn')
                    ->label('Nomor Seri Perangkat')
                    ->required(),
                Textarea::make('content')
                    ->label('Perintah')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('commit_time')
                    ->label('Waktu Commit')
                    ->required(),
                DateTimePicker::make('trans_time')
                    ->label('Waktu Transaksi'),
                TextInput::make('return_value')
                    ->label('Nilai Kembali')
                    ->default(null),
            ]);
    }
}
