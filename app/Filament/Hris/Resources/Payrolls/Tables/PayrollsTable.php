<?php

namespace App\Filament\Hris\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period_start')
                    ->label('Awal Periode')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('period_end')
                    ->label('Akhir Periode')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('allowance_transport')
                    ->label('Tunj. Transport')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('allowance_meal')
                    ->label('Tunj. Makan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bpjs_kesehatan')
                    ->label('BPJS Kes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bpjs_ketenagakerjaan')
                    ->label('BPJS TK')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pph21')
                    ->label('PPh 21')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('net_salary')
                    ->label('Gaji Bersih')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('employee_id')
                    ->label('Karyawan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
