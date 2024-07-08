<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Endpoint;

class SubscriptionResponse
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        /** @var string[] $keys */
        public readonly array $keys,
    ) {
    }
}
