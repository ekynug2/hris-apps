<?php

namespace App\Filament\Hris\Resources\DeviceCommands\Tables;


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class DeviceCommandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device_sn')
                    ->label('Nomor Seri')
                    ->searchable(),
                TextColumn::make('content')
                    ->label('Perintah')
                    ->searchable(),
                TextColumn::make('commit_time')
                    ->label('Waktu Commit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('trans_time')
                    ->label('Waktu Transaksi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('return_value')
                    ->label('Nilai Kembali')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
