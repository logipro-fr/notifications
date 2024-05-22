<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Subscriber\InMemorySubscriberRepository;
use PHPUnit\Framework\TestCase;

class InMemorySubscriberRepositoryTest extends TestCase
{
    protected InMemorySubscriberRepository $repository;
    protected Subscriber $subscriber;
    protected function setUp(): void
    {
        $this->repository = new InMemorySubscriberRepository();
        $this->subscriber = new Subscriber();
    }
    public function testAddSubscriber(): void
    {
        $this->repository->add($this->subscriber);

        $this->assertCount(1, $this->repository->getAll());
        $this->assertSame($this->subscriber, $this->repository->getAll()[0]);
    }

    public function testRemoveSubscriber(): void
    {
        $this->repository->add($this->subscriber);

        $this->repository->remove($this->subscriber);

        $this->assertCount(0, $this->repository->getAll());
    }

    public function testGetAllSubscribers(): void
    {
        $subscriber1 = new Subscriber();
        $subscriber2 = new Subscriber();

        $this->repository->add($subscriber1);
        $this->repository->add($subscriber2);

        $subscribers = $this->repository->getAll();

        $this->assertCount(2, $subscribers);
        $this->assertContains($subscriber1, $subscribers);
        $this->assertContains($subscriber2, $subscribers);
    }

    public function testRemoveNonExistingSubscriber(): void
    {
        $subscriber1 = new Subscriber();
        $subscriber2 = new Subscriber();

        $this->repository->add($subscriber1);

        $this->repository->remove($subscriber2);

        $this->assertCount(1, $this->repository->getAll());
        $this->assertSame($subscriber1, $this->repository->getAll()[0]);
    }
}
