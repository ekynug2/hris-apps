<?php

namespace App\Filament\Hris\Resources\OrganizationUnits\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrganizationUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Unit')
                    ->required(),
                TextInput::make('type')
                    ->label('Tipe')
                    ->required(),
                TextInput::make('parent_id')
                    ->label('Unit Induk')
                    ->numeric()
                    ->default(null),
                Textarea::make('meta')
                    ->label('Meta Data')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
