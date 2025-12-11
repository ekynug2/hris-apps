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
                    ->label('Tipe Dokumen')
                    ->options([
                        'Identity Card' => 'KTP',
                        'Family Card' => 'Kartu Keluarga (KK)',
                        'Education Certificate' => 'Ijazah',
                        'Contract' => 'Kontrak Kerja',
                        'Resume' => 'Resume / CV',
                        'Other' => 'Lainnya',
                    ])
                    ->required(),
                FileUpload::make('file_path')
                    ->label('Berkas')
                    ->required()
                    ->disk('public')
                    ->directory('documents'),
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'first_name')
                    ->required(),
                Hidden::make('uploaded_at')
                    ->default(now()),
                Hidden::make('uploaded_by')
                    ->default(fn() => auth()->id()),
            ]);
    }
}
