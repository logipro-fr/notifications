<?php

namespace Notifications\Tests\Domain\Entity\Publisher;

use Notifications\Domain\Entity\Publisher\Publisher;

class PublisherFake extends Publisher
{
    public function countSubscriber(): int
    {
        return count($this->subscribers);
    }
}
