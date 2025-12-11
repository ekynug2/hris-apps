<?php

namespace App\Filament\Hris\Widgets;

use App\Models\Attendance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAttendance extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->whereDate('date', now())
                    ->orderBy('clock_in', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('employee.first_name')
                    ->label('Karyawan')
                    ->description(fn(Attendance $record) => $record->employee->nik),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('clock_in')
                    ->label('Masuk')
                    ->time(),
                Tables\Columns\TextColumn::make('clock_out')
                    ->label('Keluar')
                    ->time(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'present',
                        'warning' => 'late',
                        'danger' => 'absent',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'absent' => 'Absen',
                        default => $state,
                    }),
            ]);
    }
}
