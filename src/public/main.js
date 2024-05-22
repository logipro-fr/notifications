navigator.serviceWorker.register("sw.js");

function enableNotif()
{
    Notification.requestPermission().then((permission) => {
        if (permission === 'granted') {
            navigator.serviceWorker.ready.then((sw) => {
                //subscribe
                sw.pushManager.subscribe({
                    userVisibleOnly:true,
                    applicationServerKey: "BO71HBUxeIRbjm7m8Ed3mC_11XRv2OIpzEykqHLCIOc2Ol1H_R9zzIwCt69wkwPbGqbqbytdvikVAa0QKFqeyiM"
                }).then((subscription) => {
                    console.log(JSON.stringify(subscription));
                    sendSubscriptionToServer(subscription);
                    displaySubscriptionInfo(subscription);
                    localStorage.setItem('pushSubscription', JSON.stringify(subscription));
                });
            });
        }
    })
}

function disableNotif()
{
    navigator.serviceWorker.ready.then((sw) => {
        sw.pushManager.getSubscription().then((subscription) => {
            if (subscription) {
                subscription.unsubscribe().then(() => {
                    console.log('Désinscription réussie');
                    removeSubscriptionFromLocalStorage();
                    document.getElementById('subscription-info').style.display = 'none';
                }).catch((error) => {
                    console.error('Erreur lors de la désinscription:', error);
                });
            }
        });
    });
}

function displaySubscriptionInfo(subscription)
{
    document.getElementById('subscription-data').textContent = JSON.stringify(subscription, null, 2);
    document.getElementById('subscription-info').style.display = 'block';
}

function sendSubscriptionToServer(subscription)
{
    fetch('subscriber.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            subscriberId: 'ID_DE_L_ABONNÉ',
            subscription: subscription
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Subscription successful:', data);
    })
    .catch(error => {
        console.error('Error subscribing:', error);
    });
}

function removeSubscriptionFromLocalStorage()
{
    localStorage.removeItem('pushSubscription');
}

        window.onload = function () {
            const storedSubscription = localStorage.getItem('pushSubscription');
            if (storedSubscription) {
                const subscription = JSON.parse(storedSubscription);
                displaySubscriptionInfo(subscription);
            }

            navigator.serviceWorker.ready.then((sw) => {
                sw.pushManager.getSubscription().then((subscription) => {
                    if (!subscription) {
                        removeSubscriptionFromLocalStorage();
                        document.getElementById('subscription-info').style.display = 'none';
                    }
                });
            });
        };

        function toggleNotification(checkbox)
        {
            if (checkbox.checked) {
                enableNotif();
            } else {
                disableNotif();
            }
        }