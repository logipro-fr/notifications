self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function (response) {
            return response || fetch(event.request);
        })
    );
});

self.addEventListener('push', function (event) {

    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const sendNotification = payload => {
        const title = payload.title || "Web Push Notification";
        const options = {
            body: payload.body || 'You have a new notification!',
            icon: payload.icon || '/path-to-default-icon.png',
            image: payload.image || '/path-to-default-image.png',
            data: {
                url: payload.url || '/',
            },
            actions: payload.actions || [],
            requireInteraction: true,
        };

        return self.registration.showNotification(title, options);
    };

    // Extract the payload from the event, if available
    if (event.data) {
        const payload = event.data.json();
        event.waitUntil(sendNotification(payload));
    }
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});