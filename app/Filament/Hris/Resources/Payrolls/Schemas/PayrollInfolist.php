<?php

namespace App\Filament\Hris\Resources\Payrolls\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PayrollInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('period_start')
                    ->date(),
                TextEntry::make('period_end')
                    ->date(),
                TextEntry::make('basic_salary')
                    ->numeric(),
                TextEntry::make('allowance_transport')
                    ->numeric(),
                TextEntry::make('allowance_meal')
                    ->numeric(),
                TextEntry::make('bpjs_kesehatan')
                    ->numeric(),
                TextEntry::make('bpjs_ketenagakerjaan')
                    ->numeric(),
                TextEntry::make('pph21')
                    ->numeric(),
                TextEntry::make('net_salary')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('rejection_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('employee_id')
                    ->numeric(),
                TextEntry::make('approved_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('details')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
