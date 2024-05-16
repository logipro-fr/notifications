<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\Subscriber;

class UnsubscriptionRequest
{
    public function __construct(public readonly string $url, public readonly array $subscriberId)
    {
    }
}
