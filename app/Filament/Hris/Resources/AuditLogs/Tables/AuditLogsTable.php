<?php

namespace App\Filament\Hris\Resources\AuditLogs\Tables;


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event_time')
                    ->label('Waktu Kejadian')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('event_type')
                    ->label('Tipe Kejadian')
                    ->searchable(),
                TextColumn::make('module')
                    ->label('Modul')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('Alamat IP')
                    ->searchable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Diperbarui Oleh')
                    ->searchable()
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
                ViewAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
