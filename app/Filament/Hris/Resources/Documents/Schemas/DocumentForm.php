<?php

namespace App\Filament\Hris\Resources\Documents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('file_path')
                    ->required(),
                DateTimePicker::make('uploaded_at')
                    ->required(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('uploaded_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
