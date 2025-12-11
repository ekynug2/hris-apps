<?php

namespace App\Filament\Hris\Resources\PayrollRuns\Pages;

use App\Filament\Hris\Resources\PayrollRuns\PayrollRunResource;
use App\Models\Payslip;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Notifications\Notification;

class ViewPayrollRun extends ViewRecord
{
    protected static string $resource = PayrollRunResource::class;

    protected static ?string $title = 'Detail Penggajian';

    protected function getHeaderActions(): array
    {
        return [
            // Recalculate Action (only for draft/in_progress)
            Actions\Action::make('recalculate')
                ->label('Hitung Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->visible(fn() => $this->record->canBeEdited())
                ->requiresConfirmation()
                ->modalHeading('Hitung Ulang Payroll')
                ->modalDescription('Semua kalkulasi akan dihitung ulang berdasarkan data terbaru. Lanjutkan?')
                ->action(function () {
                    foreach ($this->record->items as $item) {
                        $item->loadFromAttendance();
                        $item->calculateTotals();
                    }
                    $this->record->recalculateTotals();

                    Notification::make()
                        ->title('Kalkulasi berhasil diperbarui')
                        ->success()
                        ->send();
                }),

            // Finalize Action (only for draft/in_progress)
            Actions\Action::make('finalize')
                ->label('Finalisasi Payroll')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn() => $this->record->canBeEdited())
                ->requiresConfirmation()
                ->modalHeading('Finalisasi Payroll')
                ->modalDescription('Setelah di-finalize, perhitungan payroll tidak bisa diubah. Lanjutkan?')
                ->modalSubmitActionLabel('Ya, Finalisasi')
                ->action(function () {
                    $this->record->update([
                        'status' => 'finalized',
                        'finalized_by' => auth()->id(),
                        'finalized_at' => now(),
                    ]);

                    Notification::make()
                        ->title('Payroll berhasil di-finalize')
                        ->success()
                        ->send();
                }),

            // Generate Payslips Action (only for finalized)
            Actions\Action::make('generate_payslips')
                ->label('Generate Slip Gaji')
                ->icon('heroicon-o-document-text')
                ->color('warning')
                ->visible(fn() => $this->record->isFinalized())
                ->form([
                    \Filament\Forms\Components\Toggle::make('publish_immediately')
                        ->label('Publish ke Portal Karyawan')
                        ->default(true),
                    \Filament\Forms\Components\Toggle::make('include_logo')
                        ->label('Sertakan Logo Perusahaan')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $count = 0;
                    foreach ($this->record->items()->where('is_included', true)->get() as $item) {
                        // Check if payslip already exists
                        if ($item->payslip)
                            continue;

                        $payslip = Payslip::create([
                            'payroll_item_id' => $item->id,
                            'employee_id' => $item->employee_id,
                            'slip_number' => Payslip::generateSlipNumber(
                                $this->record->period_start->year,
                                $this->record->period_start->month
                            ),
                            'period_start' => $this->record->period_start,
                            'period_end' => $this->record->period_end,
                            'pay_date' => $this->record->pay_date,
                            'gross_pay' => $item->gross_pay,
                            'total_deductions' => $item->total_deductions,
                            'net_pay' => $item->net_pay,
                            'status' => $data['publish_immediately'] ? 'published' : 'generated',
                            'is_published' => $data['publish_immediately'],
                            'published_at' => $data['publish_immediately'] ? now() : null,
                        ]);

                        $payslip->createDataSnapshot($item);
                        $count++;
                    }

                    Notification::make()
                        ->title("{$count} slip gaji berhasil dibuat")
                        ->success()
                        ->send();
                }),

            // Export to Excel
            Actions\Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(fn() => $this->record->isFinalized()),

            Actions\EditAction::make()
                ->visible(fn() => $this->record->canBeEdited()),

            Actions\DeleteAction::make()
                ->visible(fn() => $this->record->isDraft()),
        ];
    }
}
