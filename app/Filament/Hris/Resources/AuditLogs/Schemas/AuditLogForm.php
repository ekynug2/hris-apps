<?php

namespace App\Filament\Hris\Resources\AuditLogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AuditLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('event_time')
                    ->required(),
                TextInput::make('event_type')
                    ->required(),
                TextInput::make('module')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('ip_address')
                    ->default(null),
                TextInput::make('user_agent')
                    ->default(null),
                TextInput::make('user_id')
                    ->label('Update by')
                    ->numeric()
                    ->default(null),
                Textarea::make('properties')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
