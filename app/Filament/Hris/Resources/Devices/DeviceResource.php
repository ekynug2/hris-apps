<?php

namespace App\Filament\Hris\Resources\Devices;

use App\Filament\Hris\Resources\Devices\Pages\CreateDevice;
use App\Filament\Hris\Resources\Devices\Pages\EditDevice;
use App\Filament\Hris\Resources\Devices\Pages\ListDevices;
use App\Filament\Hris\Resources\Devices\Schemas\DeviceForm;
use App\Filament\Hris\Resources\Devices\Tables\DevicesTable;
use App\Models\Device;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = "Pengaturan Sistem";
    protected static ?string $modelLabel = 'Perangkat';
    protected static ?string $pluralModelLabel = 'Perangkat';
    protected static ?string $model = Device::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Schema $schema): Schema
    {
        return DeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevicesTable::configure($table);
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
            'index' => ListDevices::route('/'),
            'create' => CreateDevice::route('/create'),
            'edit' => EditDevice::route('/{record}/edit'),
        ];
    }
}
