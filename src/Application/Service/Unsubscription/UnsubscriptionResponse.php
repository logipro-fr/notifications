<?php

namespace Notifications\Application\Service\Unsubscription;

class UnsubscriptionResponse
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
