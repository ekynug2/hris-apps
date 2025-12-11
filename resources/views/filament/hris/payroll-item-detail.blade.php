<div class="space-y-6">
    {{-- Employee Info --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <div class="flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($item->employee->first_name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $item->employee->first_name }} {{ $item->employee->last_name }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->employee->nik }} · {{ $item->employee->position->title ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Attendance Summary --}}
    <div class="grid grid-cols-5 gap-4">
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $item->working_days }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Hari Kerja</p>
        </div>
        <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $item->present_days }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Hadir</p>
        </div>
        <div class="bg-red-50 dark:bg-red-900/30 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $item->absent_days }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Absen</p>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900/30 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $item->late_count }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Terlambat</p>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                {{ number_format($item->overtime_hours, 1) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Jam Lembur</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        {{-- Earnings --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-green-500 text-white px-4 py-2 font-semibold">
                Pendapatan
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($item->getEarningsBreakdown() as $label => $value)
                    @if($value > 0)
                        <div class="flex justify-between px-4 py-2 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ $label }}</span>
                            <span class="text-gray-900 dark:text-white font-medium">Rp
                                {{ number_format($value, 0, ',', '.') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="bg-green-50 dark:bg-green-900/30 px-4 py-3 flex justify-between font-bold">
                <span class="text-green-700 dark:text-green-400">Total Pendapatan</span>
                <span class="text-green-700 dark:text-green-400">Rp
                    {{ number_format($item->gross_pay, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Deductions --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-red-500 text-white px-4 py-2 font-semibold">
                Potongan
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($item->getDeductionsBreakdown() as $label => $value)
                    @if($value > 0)
                        <div class="flex justify-between px-4 py-2 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">{{ $label }}</span>
                            <span class="text-red-600 dark:text-red-400 font-medium">- Rp
                                {{ number_format($value, 0, ',', '.') }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="bg-red-50 dark:bg-red-900/30 px-4 py-3 flex justify-between font-bold">
                <span class="text-red-700 dark:text-red-400">Total Potongan</span>
                <span class="text-red-700 dark:text-red-400">- Rp
                    {{ number_format($item->total_deductions, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Net Pay Summary --}}
    <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-lg p-6 text-center text-white">
        <p class="text-sm uppercase tracking-wide opacity-80">Take Home Pay</p>
        <p class="text-4xl font-bold mt-1">Rp {{ number_format($item->net_pay, 0, ',', '.') }}</p>
    </div>

    {{-- Adjustment Note --}}
    @if($item->adjustment_reason)
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Catatan Penyesuaian:</p>
            <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">{{ $item->adjustment_reason }}</p>
        </div>
    @endif
</div>