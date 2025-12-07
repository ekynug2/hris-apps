<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-2 mb-4">
            <div class="p-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-sm">
                <x-heroicon-o-sparkles class="w-5 h-5 text-white" />
            </div>
            <h2 class="text-lg font-bold text-gray-800 dark:text-white">AI Attendance Insights</h2>
            <span
                class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200">
                BETA
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($insights as $insight)
                @php
                    $colors = match ($insight['type']) {
                        'success' => 'bg-green-50 border-green-200 text-green-700',
                        'warning' => 'bg-amber-50 border-amber-200 text-amber-700',
                        'danger' => 'bg-red-50 border-red-200 text-red-700',
                        'info' => 'bg-blue-50 border-blue-200 text-blue-700',
                        default => 'bg-gray-50 border-gray-200 text-gray-700',
                    };
                    $iconColor = match ($insight['type']) {
                        'success' => 'text-green-600',
                        'warning' => 'text-amber-600',
                        'danger' => 'text-red-600',
                        'info' => 'text-blue-600',
                        default => 'text-gray-600',
                    };
                @endphp
                <div class="flex items-start gap-4 p-4 rounded-xl border {{ $colors }}">
                    <div class="shrink-0 mt-1">
                        @svg($insight['icon'], 'w-4 h-4 ' . $iconColor)
                    </div>
                    <div>
                        <h3 class="font-bold text-sm">{{ $insight['title'] }}</h3>
                        <p class="text-sm mt-1 opacity-90 leading-relaxed">{{ $insight['message'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 text-xs text-gray-400 text-right">
            Analysis based on last 7 days of data.
        </div>
    </x-filament::section>
</x-filament-widgets::widget>