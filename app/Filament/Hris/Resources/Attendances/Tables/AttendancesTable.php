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
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('clock_in')
                    ->time()
                    ->sortable(),
                TextColumn::make('clock_in_method')
                    ->label('In Method')
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
                    ->time()
                    ->sortable(),
                TextColumn::make('clock_out_method')
                    ->label('Out Method')
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
                    ->badge(),
                //TextColumn::make('lat')
                //    ->searchable(),
                //TextColumn::make('lng')
                //    ->searchable(),
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
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('date_from'),
                        DatePicker::make('date_to'),
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
            ->headerActions([
                \pxlrbt\FilamentExcel\Actions\Tables\ExportAction::make()
                    ->exports([
                        \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                            ->fromTable()
                            ->withFilename('attendances-' . date('Y-m-d-H-i-s'))
                            ->withColumns([
                                \pxlrbt\FilamentExcel\Columns\Column::make('employee.nik')->heading('NIK'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('employee.first_name')->heading('Name'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('date')->heading('Date'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('clock_in')->heading('Clock In'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('clock_in_method')->heading('In Method'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('clock_out')->heading('Clock Out'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('clock_out_method')->heading('Out Method'),
                                \pxlrbt\FilamentExcel\Columns\Column::make('status')->heading('Status'),
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
