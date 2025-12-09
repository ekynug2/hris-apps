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
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('event_type')
                    ->searchable(),
                TextColumn::make('module')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->searchable(),
                TextColumn::make('user_agent')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Update by')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
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
