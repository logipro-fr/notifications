<?php

namespace Notifications\Application\Service\Subscription;

class SubscriptionRequest
{
    /**
    * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $url
    */
    public function __construct(public readonly array $url)
    {
    }
}
