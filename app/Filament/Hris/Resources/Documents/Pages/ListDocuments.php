<?php

namespace App\Filament\Hris\Resources\Documents\Pages;

use App\Filament\Hris\Resources\Documents\DocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected $queryString = [];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
