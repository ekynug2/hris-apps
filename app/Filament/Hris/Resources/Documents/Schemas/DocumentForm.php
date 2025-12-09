<?php

namespace App\Filament\Hris\Resources\Documents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options([
                        'Identity Card' => 'Identity Card',
                        'Family Card' => 'Family Card',
                        'Education Certificate' => 'Education Certificate',
                        'Contract' => 'Contract',
                        'Resume' => 'Resume',
                        'Other' => 'Other',
                    ])
                    ->required(),
                FileUpload::make('file_path')
                    ->required()
                    ->disk('public')
                    ->directory('documents'),
                Select::make('employee_id')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Hidden::make('uploaded_at')
                    ->default(now()),
                Hidden::make('uploaded_by')
                    ->default(fn() => auth()->id()),
            ]);
    }
}
