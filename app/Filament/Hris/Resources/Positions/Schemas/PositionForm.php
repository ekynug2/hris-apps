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
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('base_salary')
                    ->required()
                    ->numeric(),
                TextInput::make('level')
                    ->required(),
                TextInput::make('department_id')
                    ->required()
                    ->numeric(),
                Textarea::make('meta')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
