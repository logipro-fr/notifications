document.addEventListener('DOMContentLoaded', () => {
  const applicationServerKey =
    'BMBlr6YznhYMX3NgcWIDRxZXs0sh7tCv7_YCsWcww0ZCv9WGg-tRCXfMEHTiBPCksSqeve1twlbmVAZFv7GSuj0';
  let isPushEnabled = false;

  const pushButton = document.querySelector('#push-subscription-button');
  if (!pushButton) {
    return;
  }

  pushButton.addEventListener('click', function() {
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
    changePushButtonState('incompatible');
    return;
  }

  navigator.serviceWorker.register('sw.js').then(
    () => {
      console.log('[SW] Service worker has been registered');
      push_updateSubscription();
    },
    e => {
      console.error('[SW] Service worker registration failed', e);
      changePushButtonState('incompatible');
    }
  );

  function changePushButtonState(state) {
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

  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }

  function checkNotificationPermission() {
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

  function push_subscribe() {
    changePushButtonState('computing');

    return checkNotificationPermission()
      .then(() => navigator.serviceWorker.ready)
      .then(serviceWorkerRegistration =>
        serviceWorkerRegistration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: urlBase64ToUint8Array(applicationServerKey),
        })
      )
      .then(subscription => {
        return push_sendSubscriptionToServer(subscription, 'POST');
      })
      .then(subscription => subscription && changePushButtonState('enabled')) 
      .then(() => sendAuthorizationStatusToServer(true))
      .catch(e => {
        if (Notification.permission === 'denied') {
          console.warn('Notifications are denied by the user.');
          sendAuthorizationStatusToServer(false);
        } 
      });
  }

  function push_updateSubscription() {
    navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {
        changePushButtonState('disabled');

        if (!subscription) {
          return;
        }
        return push_sendSubscriptionToServer(subscription, 'PUT');
      })
      .then(subscription => subscription && changePushButtonState('enabled'))
      .catch(e => {
        console.error('Error when updating the subscription', e);
      });
  }

  function push_unsubscribe() {
    changePushButtonState('computing');

    navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {
        if (!subscription) {
          changePushButtonState('disabled');
          return;
        }
        return push_sendSubscriptionToServer(subscription, 'DELETE');
      })
      .then(subscription => subscription.unsubscribe())
      .then(() => changePushButtonState('disabled'))
      .then(() => sendAuthorizationStatusToServer(false))
      .catch(e => {
        console.error('Error when unsubscribing the user', e);
        changePushButtonState('disabled');
      });
  }

  async function push_sendSubscriptionToServer(subscription, method) {
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
    console.log("Enregistrement de data: ");
    console.log(data);

    const url = 'http://172.17.0.1:11480/api/v1/subscriber/register';
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });

    const result = await response.json();
    if (response.ok) {
        console.log('Subscription successful:', result);
    } else {
        console.error('Subscription failed:', result);
    }
    
  }

  async function sendAuthorizationStatusToServer(AuthorizedStatus) {
    const response = await fetch('http://172.17.0.1:11480/api/notification/authorization', {
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

  sendPushButton.addEventListener('click', () =>
    navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {
        if (!subscription) {
          alert('Please enable push notifications');
          return;
        }

        const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];
        const jsonSubscription = subscription.toJSON();
        fetch('send_push_notification.php', {
          method: 'POST',
          body: JSON.stringify(Object.assign(jsonSubscription, { contentEncoding })),
        });
      })
  );
});