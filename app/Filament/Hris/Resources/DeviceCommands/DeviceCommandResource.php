<?php

namespace App\Filament\Hris\Resources\DeviceCommands;

use App\Filament\Hris\Resources\DeviceCommands\Pages\CreateDeviceCommand;
use App\Filament\Hris\Resources\DeviceCommands\Pages\EditDeviceCommand;
use App\Filament\Hris\Resources\DeviceCommands\Pages\ListDeviceCommands;
use App\Filament\Hris\Resources\DeviceCommands\Schemas\DeviceCommandForm;
use App\Filament\Hris\Resources\DeviceCommands\Tables\DeviceCommandsTable;
use App\Models\DeviceCommand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceCommandResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "System Settings";
    protected static ?string $model = DeviceCommand::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    public static function form(Schema $schema): Schema
    {
        return DeviceCommandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeviceCommandsTable::configure($table);
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
            'index' => ListDeviceCommands::route('/'),
            'create' => CreateDeviceCommand::route('/create'),
            'edit' => EditDeviceCommand::route('/{record}/edit'),
        ];
    }
}
