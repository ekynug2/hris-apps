<?php

namespace App\Filament\Hris\Resources\Payslips\Pages;

use App\Filament\Hris\Resources\Payslips\PayslipResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewPayslip extends ViewRecord
{
    protected static string $resource = PayslipResource::class;

    protected static ?string $title = 'Lihat Slip Gaji';

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Mark as viewed when employee opens the slip
        if ($this->record && method_exists($this->record, 'markAsViewed')) {
            $this->record->markAsViewed();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label('Unduh PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $this->record->markAsDownloaded();
                    // TODO: Generate and download PDF
                }),

            Action::make('print')
                ->label('Cetak')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->extraAttributes([
                    'onclick' => 'window.print()',
                ]),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            //
        ];
    }
}
