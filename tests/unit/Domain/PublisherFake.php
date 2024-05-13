<?php

namespace Notifications\Tests\Domain;

use Notifications\Domain\Publisher;

class PublisherFake extends Publisher
{
    public function countSubscriber(): int
    {
        return count($this->subscribers);
    }
}
