<?php

namespace App\Filament\Hris\Resources\Documents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipe Dokumen')
                    ->searchable(),
                TextColumn::make('file_path')
                    ->label('Berkas')
                    ->searchable(),
                TextColumn::make('uploaded_at')
                    ->label('Diunggah Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('employee_id')
                    ->label('Karyawan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('uploaded_by')
                    ->label('Diunggah Oleh')
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
