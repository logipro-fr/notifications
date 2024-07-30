<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\Model\Subscriber\Endpoint;

class UnsubscriptionRequest
{
    public function __construct(
        public readonly Endpoint $endpoint,
    ) {
    }
}
