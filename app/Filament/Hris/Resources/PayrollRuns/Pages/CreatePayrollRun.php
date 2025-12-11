<?php

namespace App\Filament\Hris\Resources\PayrollRuns\Pages;

use App\Filament\Hris\Resources\PayrollRuns\PayrollRunResource;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\PayrollItem;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;

class CreatePayrollRun extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = PayrollRunResource::class;

    protected static ?string $title = 'Buat Penggajian';

    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    protected function getSteps(): array
    {
        return [
            // Step 1: Define Period
            Step::make('Periode')
                ->description('Tentukan periode payroll')
                ->icon('heroicon-o-calendar')
                ->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Payroll')
                                ->required()
                                ->maxLength(255)
                                ->default(fn() => 'Payroll - ' . Carbon::now()->translatedFormat('F Y'))
                                ->placeholder('Payroll - Desember 2025'),

                            Grid::make(3)
                                ->schema([
                                    DatePicker::make('period_start')
                                        ->label('Tanggal Mulai Periode')
                                        ->required()
                                        ->default(fn() => Carbon::now()->startOfMonth()),
                                    DatePicker::make('period_end')
                                        ->label('Tanggal Akhir Periode')
                                        ->required()
                                        ->default(fn() => Carbon::now()->endOfMonth()),
                                    DatePicker::make('pay_date')
                                        ->label('Tanggal Pembayaran')
                                        ->required()
                                        ->default(fn() => Carbon::now()->endOfMonth()->day(25)),
                                ]),

                            Fieldset::make('Scope (Opsional)')
                                ->schema([
                                    Select::make('organization_unit_id')
                                        ->label('Unit Organisasi')
                                        ->relationship('organizationUnit', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Semua Unit'),
                                    Select::make('department_id')
                                        ->label('Departemen')
                                        ->relationship('department', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->placeholder('Semua Departemen'),
                                ])
                                ->columns(2),
                        ]),
                ])
                ->afterValidation(function () {
                    // Store period data for next step
                }),

            // Step 2: Select Employees
            Step::make('Karyawan')
                ->description('Pilih karyawan yang akan diproses')
                ->icon('heroicon-o-users')
                ->schema([
                    Section::make()
                        ->schema([
                            Placeholder::make('employee_info')
                                ->label('')
                                ->content(function ($get) {
                                    $deptId = $get('department_id');
                                    $orgId = $get('organization_unit_id');

                                    $query = Employee::query()
                                        ->whereIn('employment_status', ['active', 'on_probation']);

                                    if ($deptId) {
                                        $query->whereHas('position', fn($q) => $q->where('department_id', $deptId));
                                    }

                                    $count = $query->count();
                                    return "Ditemukan {$count} karyawan aktif yang dapat diproses.";
                                }),

                            CheckboxList::make('selected_employees')
                                ->label('Pilih Karyawan')
                                ->options(function ($get) {
                                    $deptId = $get('department_id');

                                    $query = Employee::query()
                                        ->whereIn('employment_status', ['active', 'on_probation'])
                                        ->with('position.department');

                                    if ($deptId) {
                                        $query->whereHas('position', fn($q) => $q->where('department_id', $deptId));
                                    }

                                    return $query->get()
                                        ->mapWithKeys(function ($emp) {
                                            $positionTitle = $emp->position->title ?? '-';
                                            return [$emp->id => "{$emp->nik} - {$emp->first_name} {$emp->last_name} ({$positionTitle})"];
                                        });
                                })
                                ->searchable()
                                ->bulkToggleable()
                                ->columns(1)
                                ->required()
                                ->default(function ($get) {
                                    $deptId = $get('department_id');

                                    $query = Employee::query()
                                        ->whereIn('employment_status', ['active', 'on_probation']);

                                    if ($deptId) {
                                        $query->whereHas('position', fn($q) => $q->where('department_id', $deptId));
                                    }

                                    return $query->pluck('id')->toArray();
                                }),
                        ]),
                ]),

            // Step 3: Components & Settings
            Step::make('Komponen')
                ->description('Pengaturan komponen gaji')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Section::make('Pengaturan Kalkulasi')
                        ->schema([
                            Toggle::make('settings.auto_calculate_bpjs')
                                ->label('Hitung BPJS Otomatis')
                                ->default(true)
                                ->helperText('BPJS Kesehatan 1%, BPJS Ketenagakerjaan 2%'),

                            Toggle::make('settings.calculate_from_attendance')
                                ->label('Hitung dari Data Kehadiran')
                                ->default(true)
                                ->helperText('Keterlambatan dan absen akan diperhitungkan'),

                            Toggle::make('settings.allow_manual_adjustment')
                                ->label('Izinkan Penyesuaian Manual')
                                ->default(true)
                                ->helperText('HR dapat mengubah nilai komponen per karyawan'),
                        ])
                        ->columns(3),

                    Section::make('Komponen Gaji')
                        ->schema([
                            Placeholder::make('components_info')
                                ->label('')
                                ->content('Komponen gaji akan dihitung berdasarkan data master karyawan dan kehadiran.'),

                            KeyValue::make('settings.additional_earnings')
                                ->label('Pendapatan Tambahan (untuk semua karyawan)')
                                ->keyLabel('Nama Komponen')
                                ->valueLabel('Nilai (Rp)')
                                ->addActionLabel('Tambah Komponen')
                                ->default([]),

                            KeyValue::make('settings.additional_deductions')
                                ->label('Potongan Tambahan (untuk semua karyawan)')
                                ->keyLabel('Nama Komponen')
                                ->valueLabel('Nilai (Rp)')
                                ->addActionLabel('Tambah Potongan')
                                ->default([]),
                        ]),
                ]),

            // Step 4: Preview & Confirm
            Step::make('Konfirmasi')
                ->description('Review dan konfirmasi')
                ->icon('heroicon-o-check-circle')
                ->schema([
                    Section::make('Ringkasan Payroll')
                        ->schema([
                            Placeholder::make('summary_name')
                                ->label('Nama Payroll')
                                ->content(fn($get) => $get('name')),

                            Placeholder::make('summary_period')
                                ->label('Periode')
                                ->content(function ($get) {
                                    $start = $get('period_start');
                                    $end = $get('period_end');
                                    if ($start && $end) {
                                        return Carbon::parse($start)->format('d M Y') . ' - ' . Carbon::parse($end)->format('d M Y');
                                    }
                                    return '-';
                                }),

                            Placeholder::make('summary_pay_date')
                                ->label('Tanggal Bayar')
                                ->content(fn($get) => $get('pay_date') ? Carbon::parse($get('pay_date'))->format('d M Y') : '-'),

                            Placeholder::make('summary_employees')
                                ->label('Jumlah Karyawan')
                                ->content(fn($get) => count($get('selected_employees') ?? [])),
                        ])
                        ->columns(4),

                    Textarea::make('notes')
                        ->label('Catatan (Opsional)')
                        ->rows(3)
                        ->placeholder('Tambahkan catatan jika diperlukan...'),

                    Placeholder::make('confirmation_note')
                        ->label('')
                        ->content('Setelah dibuat, payroll akan berstatus Draft dan Anda dapat melanjutkan untuk menghitung gaji karyawan.')
                        ->extraAttributes(['class' => 'text-sm text-gray-500']),
                ]),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'draft';
        $data['created_by'] = auth()->id();
        $data['total_employees'] = count($data['selected_employees'] ?? []);

        return $data;
    }

    protected function afterCreate(): void
    {
        $payrollRun = $this->record;
        $selectedEmployees = $this->data['selected_employees'] ?? [];
        $settings = $this->data['settings'] ?? [];

        // Create PayrollItems for each selected employee
        foreach ($selectedEmployees as $employeeId) {
            $employee = Employee::with('position')->find($employeeId);
            if (!$employee)
                continue;

            $item = PayrollItem::create([
                'payroll_run_id' => $payrollRun->id,
                'employee_id' => $employeeId,
                'basic_salary' => $employee->position->base_salary ?? 0,
                'is_included' => true,
            ]);

            // Load attendance data if enabled
            if ($settings['calculate_from_attendance'] ?? true) {
                $item->loadFromAttendance();
            }

            // Calculate BPJS if enabled
            if ($settings['auto_calculate_bpjs'] ?? true) {
                $baseSalary = $item->basic_salary;
                $item->bpjs_kesehatan = $baseSalary * 0.01; // 1%
                $item->bpjs_ketenagakerjaan = $baseSalary * 0.02; // 2%
            }

            // Calculate totals
            $item->calculateTotals();
        }

        // Recalculate run totals
        $payrollRun->recalculateTotals();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
