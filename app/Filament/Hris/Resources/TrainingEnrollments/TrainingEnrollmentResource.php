<?php

namespace App\Filament\Hris\Resources\TrainingEnrollments;

use App\Filament\Hris\Resources\TrainingEnrollments\Pages\CreateTrainingEnrollment;
use App\Filament\Hris\Resources\TrainingEnrollments\Pages\EditTrainingEnrollment;
use App\Filament\Hris\Resources\TrainingEnrollments\Pages\ListTrainingEnrollments;
use App\Filament\Hris\Resources\TrainingEnrollments\Schemas\TrainingEnrollmentForm;
use App\Filament\Hris\Resources\TrainingEnrollments\Tables\TrainingEnrollmentsTable;
use App\Models\TrainingEnrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingEnrollmentResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Learning";
    protected static ?string $model = TrainingEnrollment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    public static function form(Schema $schema): Schema
    {
        return TrainingEnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingEnrollmentsTable::configure($table);
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
            'index' => ListTrainingEnrollments::route('/'),
            'create' => CreateTrainingEnrollment::route('/create'),
            'edit' => EditTrainingEnrollment::route('/{record}/edit'),
        ];
    }
}
