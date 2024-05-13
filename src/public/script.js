const checkPermission = () => {
    if (!('serviceWorker' in navigator)) {
        throw new Error("No support for service worker!")
    }

    if (!('Notification' in window)) {
        throw new Error("No support for notification API");
    }

    if (!('PushManager' in window)) {
        throw new Error("No support for Push API")
    }
    return 1;
}

const registerSW = async() => {
    const registration = await navigator.serviceWorker.register('sw.js');
    return registration;
}

const requestNotificationPermission = async() => {
    const permission = await Notification.requestPermission();
    if (permission !== 'granted') {
        throw new Error("Notification permission not granted")
    } else {
        signingSucessfullNotification();
    }
}
function signingSucessfullNotification()
{
        const notifTitle = "Logipro";
        const notifBody = `Successful registration to the notification system`;
        const notifImg = `logipro.png`;
        const options = {
            body: notifBody,
            icon: notifImg,
    };
        new Notification(notifTitle, options);
}


const main = async() => {
    checkPermission();
    await requestNotificationPermission()
    const reg = await registerSW()
}

main()