<?php

namespace Notifications\Application\Service;

class SubscriptionResponse
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        /**
         * @var array<string, string> $keys
         */
        public readonly array $keys
    ) {
    }
}
