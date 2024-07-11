<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Keys;

class SubscriptionResponse
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        /*@var array<string, string>*/
        public readonly array $keys
    ) {
    }
}
