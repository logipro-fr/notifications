<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Endpoint;

class SubscriptionResponse
{
    public function __construct(
        public readonly Endpoint $endpoint,
        public readonly string $statusMessage
    ) {
    }
}
