<?php

namespace App\Filament\Hris\Resources\Positions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Jabatan')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('base_salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric(),
                TextInput::make('level')
                    ->label('Level')
                    ->required(),
                TextInput::make('department_id')
                    ->label('ID Departemen')
                    ->required()
                    ->numeric(),
                Textarea::make('meta')
                    ->label('Meta Data')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
