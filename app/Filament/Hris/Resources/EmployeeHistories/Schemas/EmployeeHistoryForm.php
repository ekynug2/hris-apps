<?php

namespace App\Filament\Hris\Resources\EmployeeHistories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EmployeeHistoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('effective_date')
                    ->label('Tanggal Efektif')
                    ->required(),
                Select::make('change_type')
                    ->label('Tipe Perubahan')
                    ->options([
                        'position' => 'Posisi / Jabatan',
                        'department' => 'Departemen',
                        'salary' => 'Gaji',
                        'status' => 'Status',
                    ])
                    ->required(),
                Textarea::make('old_value')
                    ->label('Nilai Lama')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('new_value')
                    ->label('Nilai Baru')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->default(null)
                    ->columnSpanFull(),
                Hidden::make('changed_by')
                    ->default(fn() => auth()->id()),
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'first_name')
                    ->required(),
            ]);
    }
}
