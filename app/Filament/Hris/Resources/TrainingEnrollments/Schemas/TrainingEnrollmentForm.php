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
                    ->label('Status')
                    ->options(['registered' => 'Terdaftar', 'completed' => 'Selesai', 'failed' => 'Gagal'])
                    ->required(),
                Textarea::make('certificate_url')
                    ->label('URL Sertifikat')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                TextInput::make('training_id')
                    ->label('ID Pelatihan')
                    ->required()
                    ->numeric(),
            ]);
    }
}
