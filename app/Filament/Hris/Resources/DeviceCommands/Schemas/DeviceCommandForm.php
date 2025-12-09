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
                    ->required(),
                Textarea::make('content')
                    ->label('Command')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('commit_time')
                    ->required(),
                DateTimePicker::make('trans_time'),
                TextInput::make('return_value')
                    ->default(null),
            ]);
    }
}
