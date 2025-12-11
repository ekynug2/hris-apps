<?php

namespace App\Filament\Hris\Resources\TrainingPrograms\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TrainingProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul / Nama Program')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->default(null)
                    ->columnSpanFull(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                TextInput::make('location')
                    ->label('Lokasi')
                    ->default(null),
            ]);
    }
}
