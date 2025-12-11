<?php

namespace App\Filament\Hris\Resources\AuditLogs\Schemas;

use DateTime;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTime::make('event_time')
                    ->label('Waktu Kejadian')
                    ->required(),
                TextInput::make('event_type')
                    ->label('Tipe Kejadian')
                    ->required(),
                TextInput::make('module')
                    ->label('Modul')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->label('Alamat IP')
                    ->default(null),
                TextInput::make('user_agent')
                    ->label('User Agent')
                    ->default(null),
                TextInput::make('user_id')
                    ->label('Diperbarui Oleh')
                    ->numeric()
                    ->default(null),
                Textarea::make('properties')
                    ->label('Properti')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
