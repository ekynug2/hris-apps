<?php

namespace App\Filament\Hris\Resources\Attendances\Tables;


use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('employee.first_name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->label('Jam Masuk')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('clock_in_method')
                    ->label('Metode Masuk')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Fingerprint' => 'success',
                        'Face' => 'info',
                        'Card' => 'warning',
                        'Password' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('clock_out')
                    ->label('Jam Keluar')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('clock_out_method')
                    ->label('Metode Keluar')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Fingerprint' => 'success',
                        'Face' => 'info',
                        'Card' => 'warning',
                        'Password' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success'),
                //TextColumn::make('lat')
                //    ->searchable(),
                //TextColumn::make('lng')
                //    ->searchable(),
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
                Filter::make('date_range')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('date_from')->label('Dari Tanggal'),
                        DatePicker::make('date_to')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date) => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_to'],
                                fn(Builder $query, $date) => $query->whereDate('date', '<=', $date),
                            );
                    })
            ])
            ->persistFiltersInSession()
            ->headerActions([
                ExportAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename('attendances-' . date('Y-m-d-H-i-s'))
                            ->withColumns([
                                Column::make('employee.nik')->heading('NIK'),
                                Column::make('employee.first_name')->heading('Nama'),
                                Column::make('date')->heading('Tanggal'),
                                Column::make('clock_in')->heading('Jam Masuk'),
                                Column::make('clock_in_method')->heading('Metode Masuk'),
                                Column::make('clock_out')->heading('Jam Keluar'),
                                Column::make('clock_out_method')->heading('Metode Keluar'),
                                Column::make('status')->heading('Status'),
                            ]),
                        /*
                        ->modifyQueryUsing(function ($query) {
                            // Check if specific filters are applied
                            $filters = request('tableFilters', []);
                            $dateFrom = $filters['date_range']['date_from'] ?? null;
                            $dateTo = $filters['date_range']['date_to'] ?? null;
                            $search = request('tableSearch');

                            // If NO filter and NO search, limit export to first page (e.g. 50 items)
                            if (empty($dateFrom) && empty($dateTo) && empty($search)) {
                                $query->limit(50); 
                            }
                        }),
                         */
                    ]),
            ])
            ->actions([
                // No edit action as per requirement
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
