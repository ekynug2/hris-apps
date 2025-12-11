<?php

namespace App\Filament\Hris\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('review_period')
                    ->label('Periode Review')
                    ->required(),
                TextInput::make('rating')
                    ->label('Penilaian (Skor)')
                    ->required()
                    ->numeric(),
                Textarea::make('comments')
                    ->label('Komentar')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                TextInput::make('reviewer_id')
                    ->label('ID Penilai')
                    ->required()
                    ->numeric(),
                Textarea::make('kpi_scores')
                    ->label('Skor KPI')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
