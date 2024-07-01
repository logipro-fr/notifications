<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;

class SubscriptionRequest
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        public readonly string $keys
    ) {
    }
}
