// Clean URL display after Livewire updates (including filter changes)
// Uses Livewire 3's hook system to detect when updates complete

(function () {
    let cleanTimeout = null;

    function cleanUrlDisplay() {
        // Only clean if there are filter/sort params in URL
        if (window.location.search &&
            (window.location.search.includes('filters') ||
                window.location.search.includes('tableSort') ||
                window.location.search.includes('tableSortColumn') ||
                window.location.search.includes('tableSearch'))) {
            const cleanPath = window.location.origin + window.location.pathname;
            history.replaceState(history.state, document.title, cleanPath);
        }
    }

    function scheduleClean() {
        if (cleanTimeout) clearTimeout(cleanTimeout);
        cleanTimeout = setTimeout(cleanUrlDisplay, 500);
    }

    // Wait for Livewire to be available
    document.addEventListener('livewire:init', function () {
        // Hook into Livewire's request lifecycle
        Livewire.hook('request', ({ respond }) => {
            respond(() => {
                // After Livewire responds, schedule URL clean
                scheduleClean();
            });
        });
    });

    // Also clean on full page navigation
    document.addEventListener('livewire:navigated', scheduleClean);

    // Clean on initial page load
    window.addEventListener('load', function () {
        setTimeout(cleanUrlDisplay, 500);
    });
})();
