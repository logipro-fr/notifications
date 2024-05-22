<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Subscriber\InMemorySubscriberRepository;
use Notifications\Infrastructure\Subscriber\SubscriberManager;
use Notifications\Infrastructure\Subscriber\SubscriberManagerInDatabase;
use Notifications\Tests\Domain\Services\KeyGenFake;
use PHPUnit\Framework\TestCase;

class SubscriberManagerTest extends TestCase
{
    protected InMemorySubscriberRepository $repository;
    protected SubscriberManager $manager;
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    protected array $fakeId;

    protected function setUp(): void
    {
        $this->repository = new InMemorySubscriberRepository();
        $this->manager = new SubscriberManager($this->repository);
        $this->fakeId = [
            "endpoint" => "https://fakeoutputadresse",
            "expirationTime" => null,
            "keys" => [
                "auth" => "",
                "p256dh" => ""
            ]
        ];
    }

    public function testAddSubscriber(): void
    {
        $subscriber = new Subscriber();

        $this->manager->addSubscriber($subscriber);

        $this->assertCount(1, $this->manager->getSubscribers());
        $this->assertSame($subscriber, $this->manager->getSubscribers()[0]);
    }

    public function testRemoveSubscriber(): void
    {
        $subscriber = new Subscriber();
        $this->manager->addSubscriber($subscriber);

        $this->manager->removeSubscriber($subscriber);

        $this->assertCount(0, $this->manager->getSubscribers());
    }

    public function testGetSubscribers(): void
    {
        $subscriber1 = new Subscriber();
        $subscriber2 = new Subscriber();

        $this->manager->addSubscriber($subscriber1);
        $this->manager->addSubscriber($subscriber2);

        $subscribers = $this->manager->getSubscribers();

        $this->assertCount(2, $subscribers);
        $this->assertContains($subscriber1, $subscribers);
        $this->assertContains($subscriber2, $subscribers);
    }

    public function testToString(): void
    {
        $subscriber1 = $this->createMock(Subscriber::class);
        $subscriber1->method('getSubscriberId')->willReturn(['key1' => '123', 'nested' => ['456', '789']]);
        $subscriber2 = $this->createMock(Subscriber::class);
        $subscriber2->method('getSubscriberId')->willReturn(['key2' => '456']);

        $this->manager->addSubscriber($subscriber1);
        $this->manager->addSubscriber($subscriber2);

        $expectedString = 'Subscribers: 123, 456, 789; 456';
        $this->assertEquals($expectedString, (string)$this->manager);

        $managerSingle = new SubscriberManager(new InMemorySubscriberRepository());
        $managerSingle->addSubscriber($subscriber1);
        $expectedSingleString = 'Subscribers: 123, 456, 789';
        $this->assertEquals($expectedSingleString, (string)$managerSingle);

        $managerEmpty = new SubscriberManager(new InMemorySubscriberRepository());
        $expectedEmptyString = 'Subscribers: ';
        $this->assertEquals($expectedEmptyString, (string)$managerEmpty);
    }

    //public function testNotifySubscribers(): void
    //{
    //    $subscriber = $this->createMock(Subscriber::class);
    //    $publisher = $this->createMock(Publisher::class);
    //    $subscriber->expects($this->once())
    //               ->method('subscribe')
    //               ->with($publisher, $subscriber->getSubscriberId());

    //    $this->manager->addSubscriber($subscriber);
    //    $this->manager->notifySubscribers($publisher);
    //}
}
