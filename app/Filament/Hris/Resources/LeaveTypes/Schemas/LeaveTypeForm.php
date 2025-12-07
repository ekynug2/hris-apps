<?php

namespace App\Filament\Hris\Resources\LeaveTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('default_days')
                    ->required()
                    ->numeric(),
                Toggle::make('requires_document')
                    ->required(),
            ]);
    }
}
