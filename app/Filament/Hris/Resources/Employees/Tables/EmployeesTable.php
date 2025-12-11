<?php

namespace App\Filament\Hris\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('first_name')
                    ->label('Nama Depan')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('Nama Belakang')
                    ->searchable(),
                TextColumn::make('date_of_birth')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->badge(),
                TextColumn::make('hire_date')
                    ->label('Tanggal Masuk')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('resignation_date')
                    ->label('Tanggal Resign')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('termination_date')
                    ->label('Tanggal Berhenti')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('employment_status')
                    ->label('Status')
                    ->badge(),
                IconColumn::make('is_blacklisted')
                    ->label('Blacklist')
                    ->boolean(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('position_id')
                    ->label('ID Posisi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('supervisor_id')
                    ->label('ID Atasan')
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
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('update_employment_status')
                        ->label('Ubah Status')
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Select::make('employment_status')
                                ->label('Status Karyawan')
                                ->options([
                                    'active' => 'Aktif',
                                    'contract' => 'Kontrak',
                                    'probation' => 'Percobaan',
                                    'terminated' => 'Diberhentikan',
                                    'resigned' => 'Resign',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn($record) => $record->update(['employment_status' => $data['employment_status']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
