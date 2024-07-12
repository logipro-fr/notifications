<?php

namespace Notifications\Tests\Infrastructure\Persistence\Subscriber;

use DoctrineTestingTools\DoctrineRepositoryTesterTrait;
use Notifications\Infrastructure\Persistence\Subscriber\SubscriberRepositoryDoctrine;

class SubscriberRepositoryDoctrineTest extends SubscriberRepositoryTestBase
{
    use DoctrineRepositoryTesterTrait;

    protected function initialize(): void
    {
        $this->initDoctrineTester();
        $this->subscriberRepository = new SubscriberRepositoryDoctrine($this->getEntityManager());
    }

    protected function setUp(): void
    {
        $this->initialize();
        $this->clearTables(['subscribers']);
    }

    public function testFlush(): void
    {
        $subscriberRepository = new SubscriberRepositoryDoctrine($this->getEntityManager());
        $subscriberRepository->flush();
        $this->assertTrue(true);
    }
}
