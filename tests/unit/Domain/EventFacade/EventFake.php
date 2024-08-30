<?php

namespace Notifications\Tests\Domain\EventFacade;

use Phariscope\Event\Psr14\Event;

/**
 * EventSent : name + past tensed verb
 */
class EventFake extends Event
{
    private string $id;

    public function __construct(string $id, \DateTimeImmutable $occuredOn = new \DateTimeImmutable())
    {
        parent::__construct($occuredOn);
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}
