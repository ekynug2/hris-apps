<?php

namespace App\Filament\Hris\Resources\Devices\Tables;

use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alias')
                    ->label('Device Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sn')
                    ->label('Serial Number')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('department.name')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('Device IP')
                    ->searchable(),
                IconColumn::make('state')
                    ->label('State')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-minus-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->default(false),
                TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('user_count')
                    ->label('User Qty.')
                    ->sortable(),
                TextColumn::make('fp_count')
                    ->label('FP Qty.')
                    ->sortable(),
                TextColumn::make('face_count')
                    ->label('Face Qty.')
                    ->sortable(),
                TextColumn::make('palm_count')
                    ->label('Palm Qty.')
                    ->sortable(),
                TextColumn::make('transaction_count')
                    ->label('Transaction Qty.')
                    ->sortable(),
                TextColumn::make('cmd_count')
                    ->label('Cmd')
                    ->state(fn($record) => \App\Models\DeviceCommand::where('device_sn', $record->sn)->whereNull('trans_time')->count())
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),

                // Data Transfer Group
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('transfer_area')
                        ->label('Transfer Area')
                        ->icon('heroicon-o-building-office-2')
                        ->form([
                            \Filament\Forms\Components\Select::make('department_id')
                                ->label('Select New Area')
                                ->options(\App\Models\Department::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $cnt = $records->count();
                            $records->each(fn($record) => $record->update(['department_id' => $data['department_id']]));

                            Notification::make()
                                ->title('Area Transferred')
                                ->body("{$cnt} devices moved to new area.")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    \Filament\Actions\BulkAction::make('upload_user_data')
                        ->label('Upload User Data')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY USERINFO', $records)),
                    \Filament\Actions\BulkAction::make('upload_fingerprint')
                        ->label('Upload Fingerprint')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY FP', $records)),
                    \Filament\Actions\BulkAction::make('upload_face')
                        ->label('Upload Face')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY FACE', $records)),
                    \Filament\Actions\BulkAction::make('upload_transaction')
                        ->label('Upload Transaction')
                        ->action(fn(Collection $records) => self::sendCommand('DATA QUERY ATTLOG', $records)),
                ])
                    ->label('Data Transfer')
                    ->icon('heroicon-o-arrow-path-rounded-square'),

                // Data Clean Group
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('clear_attendance')
                        ->label('Clear Attendance Data')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR DATA ATTLOG', $records)),
                    \Filament\Actions\BulkAction::make('clear_image')
                        ->label('Clear the captured image')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR DATA LOG', $records)), // Verify command later
                    \Filament\Actions\BulkAction::make('clear_all')
                        ->label('Clear All Data')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn(Collection $records) => self::sendCommand('CLEAR ALL DATA', $records)),
                ])
                    ->label('Data Clean')
                    ->icon('heroicon-o-trash'),

                // Device Menu Group
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('reboot')
                        ->label('Reboot')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('REBOOT', $records)),
                    \Filament\Actions\BulkAction::make('read_info')
                        ->label('Read Information')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    \Filament\Actions\BulkAction::make('enroll_remote')
                        ->label('Enroll Remotely') // Requires args usually
                        ->action(fn(Collection $records) => self::sendCommand('ENROLL_FP', $records)),
                    \Filament\Actions\BulkAction::make('duplicate_punch')
                        ->label('Duplicate Punch Period') // Complex
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    \Filament\Actions\BulkAction::make('capture_setting')
                        ->label('Capture Setting')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    \Filament\Actions\BulkAction::make('upgrade_fw')
                        ->label('Upgrade Firmware')
                        ->requiresConfirmation()
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)), // Placeholder
                    \Filament\Actions\BulkAction::make('dst')
                        ->label('Daylight Saving Time')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                    \Filament\Actions\BulkAction::make('punch_state')
                        ->label('Punch State Change Setting')
                        ->action(fn(Collection $records) => self::sendCommand('INFO', $records)),
                ])
                    ->label('Device Menu')
                    ->icon('heroicon-o-bars-3'),
            ]);
    }

    protected static function sendCommand(string $commandContent, Collection $records)
    {
        $count = 0;
        foreach ($records as $device) {
            \App\Models\DeviceCommand::create([
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
