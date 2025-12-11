<?php

namespace App\Filament\Hris\Resources\PayrollRuns\Pages;

use App\Filament\Hris\Resources\PayrollRuns\PayrollRunResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditPayrollRun extends EditRecord
{
    protected static string $resource = PayrollRunResource::class;

    protected static ?string $title = 'Edit Penggajian';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(fn() => $this->record->isDraft()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
