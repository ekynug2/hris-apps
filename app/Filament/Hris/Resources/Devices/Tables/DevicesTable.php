<?php

namespace App\Filament\Hris\Resources\Devices\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
//use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use App\Models\Device;
use App\Models\DeviceCommand;
use App\Models\Department;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alias')
                    ->label('Nama Perangkat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sn')
                    ->label('Nomor Seri')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('department.name')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('IP Perangkat')
                    ->searchable(),
                TextColumn::make('computed_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        Device::STATUS_ONLINE => 'success',
                        Device::STATUS_OFFLINE => 'danger',
                        Device::STATUS_UNAUTHORIZED => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),
                TextColumn::make('last_activity')
                    ->label('Aktivitas Terakhir')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user_count')
                    ->label('Jml User')
                    ->sortable(),
                TextColumn::make('fp_count')
                    ->label('Jml Sidik Jari')
                    ->sortable(),
                TextColumn::make('face_count')
                    ->label('Jml Wajah')
                    ->sortable(),
                TextColumn::make('palm_count')
                    ->label('Jml Telapak')
                    ->sortable(),
                TextColumn::make('transaction_count')
                    ->label('Jml Transaksi')
                    ->sortable(),
                TextColumn::make('cmd_count')
                    ->label('Perintah')
                    ->state(fn($record) => DeviceCommand::where('device_sn', $record->sn)->whereNull('trans_time')->count())
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),

                // Data Transfer Group
                BulkActionGroup::make([
                    BulkAction::make('transfer_area')
                        ->label('Pindah Area')
                        ->icon('heroicon-o-building-office-2')
                        ->form([
                            Select::make('department_id')
                                ->label('Pilih Area Baru')
                                ->options(Department::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $cnt = $records->count();
                            $records->each(fn($record) => $record->update(['department_id' => $data['department_id']]));

                            Notification::make()
                                ->title('Area Dipindahkan')
                                ->body("{$cnt} perangkat dipindahkan ke area baru.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('upload_user_data')
                        ->label('Upload Data User')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY USERINFO', $records)),
                    BulkAction::make('upload_fingerprint')
                        ->label('Upload Sidik Jari')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY FP', $records)),
                    BulkAction::make('upload_face')
                        ->label('Upload Wajah')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY FACE', $records)),
                    BulkAction::make('upload_transaction')
                        ->label('Upload Transaksi')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY ATTLOG', $records)),
                ])
                    ->label('Transfer Data')
                    ->icon('heroicon-o-arrow-path-rounded-square'),

                // Data Clean Group
                BulkActionGroup::make([
                    BulkAction::make('clear_attendance')
                        ->label('Hapus Data Absensi')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR DATA ATTLOG', $records)),
                    BulkAction::make('clear_image')
                        ->label('Hapus Gambar')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR DATA LOG', $records)), // Verify command later
                    BulkAction::make('clear_all')
                        ->label('Hapus Semua Data')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR ALL DATA', $records)),
                ])
                    ->label('Bersihkan Data')
                    ->icon('heroicon-o-trash'),

                // Device Menu Group
                BulkActionGroup::make([
                    BulkAction::make('reboot')
                        ->label('Reboot')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('REBOOT', $records)),
                    BulkAction::make('read_info')
                        ->label('Baca Informasi')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    BulkAction::make('enroll_remote')
                        ->label('Daftar Jarak Jauh') // Requires args usually
                        ->action(fn(Collection $records) => self::sendCommand('ENROLL_FP', $records)),
                    BulkAction::make('duplicate_punch')
                        ->label('Duplikat Periode Punch') // Complex
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    BulkAction::make('capture_setting')
                        ->label('Pengaturan Capture')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    BulkAction::make('upgrade_fw')
                        ->label('Upgrade Firmware')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)), // Placeholder
                    BulkAction::make('dst')
                        ->label('Daylight Saving Time')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    BulkAction::make('punch_state')
                        ->label('Pengaturan Punch State')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                ])
                    ->label('Menu Perangkat')
                    ->icon('heroicon-o-bars-3'),
            ]);
    }

    protected static function sendCommand(string $commandContent, Collection $records)
    {
        $count = 0;
        foreach ($records as $device) {
            DeviceCommand::create([
                'device_sn' => $device->sn,
                'content' => $commandContent,
                'trans_time' => null, // null means pending
                'return_value' => null,
            ]);
            $count++;
        }

        Notification::make()
            ->title('Command Queued')
            ->body("Sent '{$commandContent}' to {$count} devices.")
            ->success()
            ->send();
    }
}
