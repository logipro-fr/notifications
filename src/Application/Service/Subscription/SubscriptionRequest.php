<?php

namespace Notifications\Application\Service\Subscription;

class SubscriptionRequest
{
    public function __construct(public readonly string $url)
    {
    }
}
