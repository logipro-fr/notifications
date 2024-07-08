<?php

namespace Notifications\Domain\Entity\Subscriber\Event;

use Phariscope\Event\Psr14\Event;

class SubscriberCreated extends Event
{
    public function __construct(public readonly string $endpoint)
    {
        parent::__construct();
    }
}
