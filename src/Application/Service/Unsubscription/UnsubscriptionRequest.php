<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\Model\Subscriber\Keys;

class UnsubscriptionRequest
{
    public function __construct(
        public readonly string $endpoint,
        public readonly string $expirationTime,
        public readonly Keys $keys,
    ) {
    }
}
