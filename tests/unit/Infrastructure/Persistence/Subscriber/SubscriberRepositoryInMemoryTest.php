<?php

namespace Notifications\Tests\Infrastructure\Persistence\Subscriber;

use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryInMemory;

class SubscriberRepositoryInMemoryTest extends SubscriberRepositoryTestBase
{
    protected function initialize(): void
    {
        $this->subscriberRepository = new SubscriberRepositoryInMemory();
    }
}
