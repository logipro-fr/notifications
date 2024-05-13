<?php

namespace Notifications\Application\Service\Subscription;

class SubscriptionResponse
{
    public function __construct(public readonly string $tokenSubscriber)
    {
    }
}
