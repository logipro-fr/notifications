<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Infrastructure\Subscriber\SubscriberController;

class SubscriberRouter
{
    private SubscriberController $subscriberController;

    public function __construct(SubscriberController $subscriberController)
    {
        $this->subscriberController = $subscriberController;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array{endpoint: string, expirationTime: ?string,
     * keys: array{auth: string, p256dh: string}, notificationAddress: array<string, string>} $data
     * @return string
     */
    public function routeRequest(string $uri, string $method, array $data): string
    {
        if ($uri === '/addSubscriber' && $method === 'POST') {
            return $this->subscriberController->subscribe($data);
        }

        if ($uri === '/getSubscribers' && $method === 'GET') {
            return $this->subscriberController->getSubscribers();
        }

        return '404 Not Found';
    }
}
