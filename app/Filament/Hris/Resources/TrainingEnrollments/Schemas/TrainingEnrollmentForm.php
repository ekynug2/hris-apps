<?php

namespace App\Filament\Hris\Resources\TrainingEnrollments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TrainingEnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options(['registered' => 'Registered', 'completed' => 'Completed', 'failed' => 'Failed'])
                    ->required(),
                Textarea::make('certificate_url')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('training_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
