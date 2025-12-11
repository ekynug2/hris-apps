<?php

namespace App\Filament\Hris\Resources\Payslips;

use App\Filament\Hris\Resources\Payslips\Pages;
use App\Models\Payslip;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;

class PayslipResource extends Resource
{
    protected static ?string $model = Payslip::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Slip Gaji';

    protected static ?string $modelLabel = 'Slip Gaji';

    protected static ?string $pluralModelLabel = 'Slip Gaji';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan & Kinerja';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('slip_number')
                    ->label('No. Slip')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee.first_name')
                    ->label('Karyawan')
                    ->formatStateUsing(fn($record) => $record->employee->first_name . ' ' . $record->employee->last_name)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_label')
                    ->label('Periode')
                    ->getStateUsing(fn($record) => $record->period_start->format('M Y')),
                Tables\Columns\TextColumn::make('net_pay')
                    ->label('Gaji Bersih')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'generated' => 'gray',
                        'published' => 'success',
                        'viewed' => 'info',
                        'downloaded' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'generated' => 'Dibuat',
                        'published' => 'Dipublish',
                        'viewed' => 'Dilihat',
                        'downloaded' => 'Diunduh',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'generated' => 'Dibuat',
                        'published' => 'Dipublish',
                        'viewed' => 'Dilihat',
                        'downloaded' => 'Diunduh',
                    ]),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => static::getUrl('view', ['record' => $record])),
                Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($record) {
                        $record->markAsDownloaded();
                    }),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Infolists\Components\Section::make('Informasi Slip Gaji')
                    ->schema([
                        Infolists\Components\TextEntry::make('slip_number')
                            ->label('No. Slip'),
                        Infolists\Components\TextEntry::make('period_start')
                            ->label('Periode')
                            ->date('F Y'),
                        Infolists\Components\TextEntry::make('pay_date')
                            ->label('Tanggal Bayar')
                            ->date('d M Y'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge(),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Data Karyawan')
                    ->schema([
                        Infolists\Components\TextEntry::make('employee.nik')
                            ->label('NIK'),
                        Infolists\Components\TextEntry::make('employee.first_name')
                            ->label('Nama')
                            ->formatStateUsing(fn($record) => $record->employee->first_name . ' ' . $record->employee->last_name),
                        Infolists\Components\TextEntry::make('employee.position.title')
                            ->label('Jabatan'),
                        Infolists\Components\TextEntry::make('employee.position.department.name')
                            ->label('Departemen'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Ringkasan Gaji')
                    ->schema([
                        Infolists\Components\TextEntry::make('gross_pay')
                            ->label('Total Pendapatan')
                            ->money('IDR')
                            ->color('success'),
                        Infolists\Components\TextEntry::make('total_deductions')
                            ->label('Total Potongan')
                            ->money('IDR')
                            ->color('danger'),
                        Infolists\Components\TextEntry::make('net_pay')
                            ->label('Gaji Diterima')
                            ->money('IDR')
                            ->weight('bold')
                            ->size('lg'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayslips::route('/'),
            'view' => Pages\ViewPayslip::route('/{record}'),
        ];
    }
}
