<?php

namespace App\Filament\Hris\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('period_start')
                    ->label('Awal Periode')
                    ->required(),
                DatePicker::make('period_end')
                    ->label('Akhir Periode')
                    ->required(),
                TextInput::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->required()
                    ->numeric(),
                TextInput::make('allowance_transport')
                    ->label('Tunjangan Transport')
                    ->required()
                    ->numeric(),
                TextInput::make('allowance_meal')
                    ->label('Tunjangan Makan')
                    ->required()
                    ->numeric(),
                TextInput::make('bpjs_kesehatan')
                    ->label('BPJS Kesehatan')
                    ->required()
                    ->numeric(),
                TextInput::make('bpjs_ketenagakerjaan')
                    ->label('BPJS Ketenagakerjaan')
                    ->required()
                    ->numeric(),
                TextInput::make('pph21')
                    ->label('PPh 21')
                    ->required()
                    ->numeric(),
                TextInput::make('net_salary')
                    ->label('Gaji Bersih')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'calculated' => 'Dihitung',
                        'approved' => 'Disetujui',
                        'paid' => 'Dibayar',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),
                Textarea::make('rejection_note')
                    ->label('Catatan Penolakan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                TextInput::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric()
                    ->default(null),
                Textarea::make('details')
                    ->label('Detail')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
