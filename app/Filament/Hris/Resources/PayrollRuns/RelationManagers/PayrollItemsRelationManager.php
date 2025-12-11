<?php

namespace App\Filament\Hris\Resources\PayrollRuns\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkAction;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class PayrollItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Detail Karyawan';

    protected static ?string $recordTitleAttribute = 'employee.first_name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Pendapatan')
                    ->schema([
                        TextInput::make('basic_salary')
                            ->label('Gaji Pokok')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('allowance_position')
                            ->label('Tunjangan Jabatan')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('allowance_transport')
                            ->label('Tunjangan Transport')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('allowance_meal')
                            ->label('Tunjangan Makan')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('overtime_pay')
                            ->label('Lembur')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('bonus')
                            ->label('Bonus')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('other_earnings')
                            ->label('Pendapatan Lain')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->columns(3),

                Section::make('Potongan')
                    ->schema([
                        TextInput::make('bpjs_kesehatan')
                            ->label('BPJS Kesehatan')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('bpjs_ketenagakerjaan')
                            ->label('BPJS Ketenagakerjaan')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('pph21')
                            ->label('PPh 21')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('loan_deduction')
                            ->label('Potongan Pinjaman')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('late_penalty')
                            ->label('Denda Keterlambatan')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('absence_deduction')
                            ->label('Potongan Absen')
                            ->numeric()
                            ->prefix('Rp'),
                        TextInput::make('other_deductions')
                            ->label('Potongan Lain')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->columns(3),

                Textarea::make('adjustment_reason')
                    ->label('Alasan Penyesuaian')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee.first_name')
            ->columns([
                Tables\Columns\TextColumn::make('employee.nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.first_name')
                    ->label('Nama')
                    ->formatStateUsing(fn($record) => $record->employee->first_name . ' ' . $record->employee->last_name)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.position.title')
                    ->label('Jabatan')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('gross_pay')
                    ->label('Gaji Kotor')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_deductions')
                    ->label('Potongan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('net_pay')
                    ->label('Gaji Bersih')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\IconColumn::make('is_included')
                    ->label('Termasuk')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_included')
                    ->label('Status Inklusi')
                    ->trueLabel('Termasuk')
                    ->falseLabel('Tidak Termasuk'),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('view_detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => 'Detail Gaji: ' . $record->employee->first_name . ' ' . $record->employee->last_name)
                    ->modalContent(fn($record) => view('filament.hris.payroll-item-detail', ['item' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                EditAction::make()
                    ->label('Edit')
                    ->visible(fn() => $this->getOwnerRecord()->canBeEdited())
                    ->after(function ($record) {
                        $record->calculateTotals();
                        $this->getOwnerRecord()->recalculateTotals();
                    }),

                Action::make('toggle_include')
                    ->label(fn($record) => $record->is_included ? 'Exclude' : 'Include')
                    ->icon(fn($record) => $record->is_included ? 'heroicon-o-minus-circle' : 'heroicon-o-plus-circle')
                    ->color(fn($record) => $record->is_included ? 'danger' : 'success')
                    ->visible(fn() => $this->getOwnerRecord()->canBeEdited())
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_included' => !$record->is_included]);
                        $this->getOwnerRecord()->recalculateTotals();

                        Notification::make()
                            ->title($record->is_included ? 'Karyawan dimasukkan ke payroll' : 'Karyawan dikeluarkan dari payroll')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                BulkAction::make('include_all')
                    ->label('Include Semua')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn() => $this->getOwnerRecord()->canBeEdited())
                    ->action(function ($records) {
                        $records->each->update(['is_included' => true]);
                        $this->getOwnerRecord()->recalculateTotals();
                    }),
                BulkAction::make('exclude_all')
                    ->label('Exclude Semua')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->visible(fn() => $this->getOwnerRecord()->canBeEdited())
                    ->action(function ($records) {
                        $records->each->update(['is_included' => false]);
                        $this->getOwnerRecord()->recalculateTotals();
                    }),
            ])
            ->defaultSort('employee.first_name');
    }
}
