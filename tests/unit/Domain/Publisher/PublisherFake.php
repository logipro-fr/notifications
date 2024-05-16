<?php

namespace Notifications\Tests\Domain\Publisher;

use Notifications\Domain\Publisher\Publisher;

class PublisherFake extends Publisher
{
    public function countSubscriber(): int
    {
        return count($this->subscribers);
    }
}
