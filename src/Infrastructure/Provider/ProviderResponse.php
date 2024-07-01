<?php

namespace Notifications\Infrastructure\Provider;

use Notifications\Domain\Entity\Subscriber\Endpoint;

class ProviderResponse
{
    public function __construct(
        public readonly Endpoint $endpoint,
    ) {
    }
}
