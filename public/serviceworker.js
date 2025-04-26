var staticCacheName = "Budget App";
var filesToCache = [
    "/images/icons/72.png",
    "/images/icons/96.png",
    "/images/icons/120.png",
    "/images/icons/128.png",
];

// Cache on install
self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then((cache) => {
            return cache.addAll(filesToCache);
        })
    );
});

// Clear old caches on activate
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name.startsWith("pwa-budget-app-"))
                    .filter((name) => name !== staticCacheName)
                    .map((name) => caches.delete(name))
            );
        })
    );
});

// Serve from cache
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches
            .match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match("/offline");
            })
    );
});
