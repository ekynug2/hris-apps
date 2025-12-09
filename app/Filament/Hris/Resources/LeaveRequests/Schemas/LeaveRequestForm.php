<?php

namespace App\Filament\Hris\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('total_days')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),
                Textarea::make('reason')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('rejection_note')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('employee_id')
                    ->required()
                    ->numeric(),
                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->required()
                    ->live(),
                FileUpload::make('attachment')
                    ->disk('public')
                    ->directory('leave_attachments')
                    ->visible(function ($get) {
                        $leaveTypeId = $get('leave_type_id');
                        if (!$leaveTypeId) {
                            return false;
                        }
                        $leaveType = \App\Models\LeaveType::find($leaveTypeId);

                        return $leaveType ? $leaveType->requires_document : false;
                    }),
                TextInput::make('approved_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('backup_approver_id')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
