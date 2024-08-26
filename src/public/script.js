document.addEventListener('DOMContentLoaded', () => {
    const applicationServerKey =
    'BMBlr6YznhYMX3NgcWIDRxZXs0sh7tCv7_YCsWcww0ZCv9WGg-tRCXfMEHTiBPCksSqeve1twlbmVAZFv7GSuj0';
    let isPushEnabled = false;

    const pushButton = document.querySelector('#push-subscription-button');
    if (!pushButton) {
        return;
    }

    pushButton.addEventListener('click', function () {
        if (isPushEnabled) {
            push_unsubscribe();
        } else {
            push_subscribe();
        }
    });

if (!('serviceWorker' in navigator)) {
    console.warn('Service workers are not supported by this browser');
    changePushButtonState('incompatible');
    return;
}

if (!('PushManager' in window)) {
    console.warn('Push notifications are not supported by this browser');
    changePushButtonState('incompatible');
    return;
}

if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
    console.warn('Notifications are not supported by this browser');
    changePushButtonState('incompatible');
    return;
}

if (Notification.permission === 'denied') {
    console.warn('Notifications are denied by the user');
    sendAuthorizationStatusToServer(false);
    changePushButtonState('incompatible');
    return;
}

  navigator.serviceWorker.register('sw.js').then(
      () => {
            console.log('[SW] Service worker has been registered');
      },
      e => {
            console.error('[SW] Service worker registration failed', e);
            changePushButtonState('incompatible');
      }
  );

function changePushButtonState(state)
{
    switch (state) {
        case 'enabled':
            pushButton.disabled = false;
            pushButton.textContent = 'Disable Push notifications';
            isPushEnabled = true;
        break;
        case 'disabled':
            pushButton.disabled = false;
            pushButton.textContent = 'Enable Push notifications';
            isPushEnabled = false;
        break;
        case 'computing':
            pushButton.disabled = true;
            pushButton.textContent = 'Loading...';
        break;
        case 'incompatible':
            pushButton.disabled = true;
            pushButton.textContent = 'Push notifications are not compatible with this browser';
        break;
        default:
            console.error('Unhandled push button state', state);
        break;
    }
}

function urlBase64ToUint8Array(base64String)
{
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

function checkNotificationPermission()
{
    return new Promise((resolve, reject) => {
        if (Notification.permission === 'denied') {
            return reject(new Error('Push messages are blocked.'));
        }

        if (Notification.permission === 'granted') {
            return resolve();
        }

        if (Notification.permission === 'default') {
            return Notification.requestPermission().then(result => {
                if (result !== 'granted') {
                    reject(new Error('Bad permission result'));
                } else {
                    resolve();
                }
            });
        }

        return reject(new Error('Unknown permission'));
    });
}

function push_subscribe()
{
    changePushButtonState('enabled');
    isPushEnabled = true;
    return checkNotificationPermission()
    .then(() => navigator.serviceWorker.ready)
    .then(
        serviceWorkerRegistration =>
        serviceWorkerRegistration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(applicationServerKey),
          })
    )
    .then(subscription => {
        return push_manageDataToServer(subscription, 'POST');
      })
    .then(subscription => subscription && changePushButtonState('enabled'))
    .then(() => sendAuthorizationStatusToServer(true))
    .catch(e => {
        if (Notification.permission === 'denied') {
            console.warn('Notifications are denied by the user.');
            sendAuthorizationStatusToServer(false);
            isPushEnabled = false;
        }
      });
}

function push_unsubscribe()
{
    changePushButtonState('disabled');
    isPushEnabled = false;
    return checkNotificationPermission()
    .then(() => navigator.serviceWorker.ready)
    .then(
        serviceWorkerRegistration =>
        serviceWorkerRegistration.pushManager.getSubscription()
    )
    .then(subscription => {
        return push_manageDataToServer(subscription, 'DELETE');
      })
    .then(subscription => subscription && changePushButtonState('enabled'))
    .then(() => sendAuthorizationStatusToServer(true))
    .catch(e => {
        if (Notification.permission === 'denied') {
            console.warn('Notifications are denied by the user.');
            sendAuthorizationStatusToServer(false);
            isPushEnabled = false;
        }
      });
}

  async function push_manageDataToServer(subscription, method)
  {
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');

    const data = {
        endpoint: subscription.endpoint,
        expirationTime: "",
        keys: {
            auth: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
            p256dh: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        },
        };

        const url = 'http://172.17.0.1:11480/api/v1/subscriber/manager';
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

    const result = await response.json();
    if (response.ok) {
        console.log('Action successful:', result);
    } else {
        console.error('Action failed:', result);
    }
  }


  async function sendAuthorizationStatusToServer(AuthorizedStatus)
  {
      const response = await fetch('http://172.17.0.1:11480/api/v1/subscriber/authorization', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ AuthorizedStatus }),
        });
      return response.json();
  }

  const sendPushButton = document.querySelector('#send-push-button');
  if (!sendPushButton) {
      return;
  }

  sendPushButton.addEventListener(
      'click',
      () =>
      navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {
            if (!subscription) {
                alert('Please enable push notifications');
                return;
            }
            sendPushButton.addEventListener('click', () => sendPushNotification());
        })
  );

  async function sendPushNotification()
  {
    try {
        const serviceWorkerRegistration = await navigator.serviceWorker.ready;
        const subscription = await serviceWorkerRegistration.pushManager.getSubscription();

        if (!subscription) {
            alert('Please enable push notifications');
            return;
        }

        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const url = document.getElementById('url').value;
        const image = document.getElementById('image').value;


        const key = subscription.getKey('p256dh');
        const token = subscription.getKey('auth');

        const data = {
            endpoint: subscription.endpoint,
            expirationTime: "",
            keys: {
                auth: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                p256dh: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
            },
            notification: {
                title: title || 'Default Title',
                description: description || 'Default Body',
                url: url || null,
                image: image || null
            }
        };

        const response = await fetch('http://172.17.0.1:11480/api/v1/subscriber/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            console.error('Failed to send push notification', await response.text());
        } else {
            console.log('Push notification sent successfully');
        }
    } catch (error) {
        console.error('Error sending push notification', error);
    }
  }
});