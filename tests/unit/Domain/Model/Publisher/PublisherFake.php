<?php

namespace Notifications\Tests\Domain\Model\Publisher;

use Notifications\Domain\Model\Publisher\Publisher;

class PublisherFake extends Publisher
{
    public function countSubscriber(): int
    {
        return count($this->subscribers);
    }
}
