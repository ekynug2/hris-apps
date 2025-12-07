<?php

namespace App\Filament\Hris\Resources\TrainingPrograms;

use App\Filament\Hris\Resources\TrainingPrograms\Pages\CreateTrainingProgram;
use App\Filament\Hris\Resources\TrainingPrograms\Pages\EditTrainingProgram;
use App\Filament\Hris\Resources\TrainingPrograms\Pages\ListTrainingPrograms;
use App\Filament\Hris\Resources\TrainingPrograms\Schemas\TrainingProgramForm;
use App\Filament\Hris\Resources\TrainingPrograms\Tables\TrainingProgramsTable;
use App\Models\TrainingProgram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingProgramResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Learning";
    protected static ?string $model = TrainingProgram::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Schema $schema): Schema
    {
        return TrainingProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingProgramsTable::configure($table);
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
            'index' => ListTrainingPrograms::route('/'),
            'create' => CreateTrainingProgram::route('/create'),
            'edit' => EditTrainingProgram::route('/{record}/edit'),
        ];
    }
}
