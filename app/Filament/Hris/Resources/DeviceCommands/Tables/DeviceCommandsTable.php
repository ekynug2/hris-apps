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
                    ->searchable(),
                TextColumn::make('content')
                    ->label('Command')
                    ->searchable(),
                TextColumn::make('commit_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('trans_time')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('return_value')
                    ->toggleable(isToggledHiddenByDefault: true),
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
