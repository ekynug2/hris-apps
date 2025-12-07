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
                    ->required(),
                TextInput::make('rating')
                    ->required()
                    ->numeric(),
                Textarea::make('comments')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                TextInput::make('reviewer_id')
                    ->required()
                    ->numeric(),
                Textarea::make('kpi_scores')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
