<?php

namespace App\Filament\Hris\Resources\Documents;

use App\Filament\Hris\Resources\Documents\Pages\CreateDocument;
use App\Filament\Hris\Resources\Documents\Pages\EditDocument;
use App\Filament\Hris\Resources\Documents\Pages\ListDocuments;
use App\Filament\Hris\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Hris\Resources\Documents\Tables\DocumentsTable;
use App\Models\Document;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Manajemen SDM";
    protected static ?string $modelLabel = 'Dokumen';
    protected static ?string $pluralModelLabel = 'Dokumen';
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function form(Schema $schema): Schema
    {
        return DocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsTable::configure($table);
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
            'index' => ListDocuments::route('/'),
            'create' => CreateDocument::route('/create'),
            'edit' => EditDocument::route('/{record}/edit'),
        ];
    }
}
