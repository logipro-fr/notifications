<?php

namespace Notifications\Application\Service\Subscription;

use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Services\KeyGeneratorStrategy;

class Subscription
{
    private SubscriptionResponse $response;
    public function __construct(private KeyGeneratorStrategy $keyGenerator)
    {
    }

    public function execute(SubscriptionRequest $request): void
    {
        $notificationAddress = new NotificationAddress($request->url);
        $publisher = new Publisher($request->url["endpoint"], $this->keyGenerator, $notificationAddress->getAddress());
        $publicKey = $publisher->getPublicKey();
        $this->response = new SubscriptionResponse($publicKey);
    }

    public function getResponse(): SubscriptionResponse
    {
        return $this->response;
    }
}
