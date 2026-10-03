self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Basic network-first strategy to fulfill PWA requirements without caching aggressively
    event.respondWith(
        fetch(event.request).catch(() => {
            return new Response('Anda sedang offline.');
        })
    );
});
