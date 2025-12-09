// Override history API to prevent query string from being added
(function () {
    const originalPushState = history.pushState;
    const originalReplaceState = history.replaceState;

    function cleanUrl(url) {
        if (typeof url === 'string' && url.includes('?')) {
            return url.split('?')[0];
        }
        if (url instanceof URL) {
            url.search = '';
            return url.href;
        }
        return url;
    }

    history.pushState = function (state, title, url) {
        if (url) {
            url = cleanUrl(url);
        }
        return originalPushState.call(this, state, title, url);
    };

    history.replaceState = function (state, title, url) {
        if (url) {
            url = cleanUrl(url);
        }
        return originalReplaceState.call(this, state, title, url);
    };

    // Also clean current URL on page load if it has query params
    if (window.location.search) {
        const cleanPath = window.location.origin + window.location.pathname;
        originalReplaceState.call(history, {}, document.title, cleanPath);
    }
})();

