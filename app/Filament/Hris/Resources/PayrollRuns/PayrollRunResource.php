<?php

namespace App\Filament\Hris\Resources\PayrollRuns;

use App\Filament\Hris\Resources\PayrollRuns\Pages;
use App\Filament\Hris\Resources\PayrollRuns\RelationManagers;
use App\Models\PayrollRun;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class PayrollRunResource extends Resource
{
    protected static ?string $model = PayrollRun::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Proses Penggajian';

    protected static ?string $modelLabel = 'Proses Penggajian';

    protected static ?string $pluralModelLabel = 'Proses Penggajian';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan & Kinerja';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Periode')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Payroll')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Payroll - Desember 2025'),
                        DatePicker::make('period_start')
                            ->label('Tanggal Mulai Periode')
                            ->required(),
                        DatePicker::make('period_end')
                            ->label('Tanggal Akhir Periode')
                            ->required(),
                        DatePicker::make('pay_date')
                            ->label('Tanggal Pembayaran')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Scope')
                    ->schema([
                        Select::make('organization_unit_id')
                            ->label('Unit Organisasi')
                            ->relationship('organizationUnit', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Unit'),
                        Select::make('department_id')
                            ->label('Departemen')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Semua Departemen'),
                    ])
                    ->columns(2),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_label')
                    ->label('Periode')
                    ->getStateUsing(fn($record) => $record->period_start->format('d M') . ' - ' . $record->period_end->format('d M Y')),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'draft' => 'gray',
                        'in_progress' => 'info',
                        'finalized' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        'draft' => 'Draft',
                        'in_progress' => 'Proses',
                        'finalized' => 'Final',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                TextColumn::make('pay_date')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('total_employees')
                    ->label('Karyawan')
                    ->alignCenter(),
                TextColumn::make('total_net_pay')
                    ->label('Total Gaji Bersih')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'in_progress' => 'Proses',
                        'finalized' => 'Final',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn($record) => $record->canBeEdited()),
                DeleteAction::make()
                    ->visible(fn($record) => $record->isDraft()),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Infolists\Components\Section::make('Informasi Payroll')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'draft' => 'gray',
                                'in_progress' => 'info',
                                'finalized' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('period_start')
                            ->label('Periode Mulai')
                            ->date('d M Y'),
                        Infolists\Components\TextEntry::make('period_end')
                            ->label('Periode Akhir')
                            ->date('d M Y'),
                        Infolists\Components\TextEntry::make('pay_date')
                            ->label('Tanggal Bayar')
                            ->date('d M Y'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Ringkasan')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_employees')
                            ->label('Total Karyawan'),
                        Infolists\Components\TextEntry::make('total_gross')
                            ->label('Total Kotor')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_deductions')
                            ->label('Total Potongan')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_net_pay')
                            ->label('Total Gaji Bersih')
                            ->money('IDR')
                            ->weight('bold'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PayrollItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrollRuns::route('/'),
            'create' => Pages\CreatePayrollRun::route('/create'),
            'view' => Pages\ViewPayrollRun::route('/{record}'),
            'edit' => Pages\EditPayrollRun::route('/{record}/edit'),
        ];
    }
}
