<?php

namespace Notifications\Application\Service;

use Notifications\Application\Service\SubscriptionRequest;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Tests\Domain\Services\KeyGenFake;

class SubscriberFactory
{
    public function buildSubscriberFromRequest(SubscriptionRequest $request): Subscriber
    {
        return new Subscriber(
            new Endpoint($request->endpoint),
            new Keys(),
            new ExpirationTime($request->expirationTime),
            new Publisher("")
        );
    }
}
