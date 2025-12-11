<?php

namespace App\Filament\Hris\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use App\Models\LeaveType;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                TextInput::make('total_days')
                    ->label('Total Hari')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->required(),
                Textarea::make('reason')
                    ->label('Alasan')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('rejection_note')
                    ->label('Catatan Penolakan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->label('ID Karyawan')
                    ->required()
                    ->numeric(),
                Select::make('leave_type_id')
                    ->label('Tipe Cuti')
                    ->relationship('leaveType', 'name')
                    ->required()
                    ->live(),
                FileUpload::make('attachment')
                    ->label('Lampiran')
                    ->disk('public')
                    ->directory('leave_attachments')
                    ->visible(function ($get) {
                        $leaveTypeId = $get('leave_type_id');
                        if (!$leaveTypeId) {
                            return false;
                        }
                        $leaveType = LeaveType::find($leaveTypeId);

                        return $leaveType ? $leaveType->requires_document : false;
                    }),
                TextInput::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric()
                    ->default(null),
                TextInput::make('backup_approver_id')
                    ->label('Penyetuju Cadangan')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
