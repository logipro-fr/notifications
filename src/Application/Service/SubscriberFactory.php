<?php

namespace Notifications\Application\Service;

use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;

class SubscriberFactory
{
    public function buildSubscriberFromRequest(SubscriptionRequest $request): Subscriber
    {
        return new Subscriber(
            new Endpoint($request->endpoint),
            new Keys($request->auth, $request->p256dh),
            new ExpirationTime($request->expirationTime),
            new Publisher("")
        );
    }

    public function buildSubscriberForDelete(UnsubscriptionRequest $request): Subscriber
    {
        return new Subscriber(
            new Endpoint($request->endpoint),
            new Keys($request->keys->getAuthKey(), $request->keys->getEncryptKey()),
            new ExpirationTime($request->expirationTime),
            new Publisher("")
        );
    }
}
