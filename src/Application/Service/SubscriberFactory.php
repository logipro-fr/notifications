<?php

namespace Notifications\Application\Service;

use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Domain\Entity\Publisher\Publisher;

use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;


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
}
