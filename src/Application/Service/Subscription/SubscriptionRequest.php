<?php

namespace Notifications\Application\Service\Subscription;

class SubscriptionRequest
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        public readonly string $auth,
        public readonly string $p256dh
    ) {
    }
}
