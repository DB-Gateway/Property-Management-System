/* Notifications only: do not cache authenticated pages or intercept requests. */
self.addEventListener('install', (event) => event.waitUntil(self.skipWaiting()));
self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

const safeUrl = (value) => {
    const fallback = new URL('dashboard', self.registration.scope);
    try {
        const target = new URL(value || fallback.href, self.registration.scope);
        return target.origin === fallback.origin && target.pathname.startsWith(new URL(self.registration.scope).pathname)
            ? target.href : fallback.href;
    } catch { return fallback.href; }
};

self.addEventListener('push', (event) => {
    let payload = {};
    try { payload = event.data?.json() || {}; } catch { /* Show a generic notification for malformed data. */ }
    event.waitUntil((async () => {
        await self.registration.showNotification(payload.title || 'PMS notification', {
            body: payload.body || 'You have a new notification. Open PMS to view it.',
            icon: new URL('images/G-logo-no-bg.png', self.registration.scope).href,
            tag: payload.id ? `pms-${payload.id}` : 'pms-notification',
            data: { url: safeUrl(payload.url) },
        });
        const windows = await self.clients.matchAll({ type: 'window', includeUncontrolled: true });
        windows.forEach((client) => client.postMessage({ type: 'pms:notification-received' }));
    })());
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil((async () => {
        const url = safeUrl(event.notification.data?.url);
        const windows = await self.clients.matchAll({ type: 'window', includeUncontrolled: true });
        const existing = windows.find((client) => client.url === url);
        if (existing) return existing.focus();
        // A new tab keeps any unfinished checklist in an existing tab intact.
        return self.clients.openWindow(url);
    })());
});
