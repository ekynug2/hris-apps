<?php

namespace App\Filament\Hris\Resources\Devices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alias')
                    ->label('Device Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sn')
                    ->label('Serial Number')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('department.name')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('Device IP')
                    ->searchable(),
                \Filament\Tables\Columns\IconColumn::make('state')
                    ->label('State')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->default(false), // Assuming 1=active/online? User image has green check. I will assume 1 is good.
                TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user_count')
                    ->label('User Qty.')
                    ->sortable(),
                TextColumn::make('fp_count')
                    ->label('FP Qty.')
                    ->sortable(),
                TextColumn::make('face_count')
                    ->label('Face Qty.')
                    ->sortable(),
                TextColumn::make('palm_count')
                    ->label('Palm Qty.')
                    ->sortable(),
                TextColumn::make('transaction_count')
                    ->label('Transaction Qty.')
                    ->sortable(),
                TextColumn::make('cmd_count')
                    ->label('Cmd')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
