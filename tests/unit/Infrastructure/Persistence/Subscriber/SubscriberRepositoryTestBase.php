<?php

namespace Notifications\Tests\Infrastructure\Persistence\Subscriber;

use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;
use PHPUnit\Framework\TestCase;

abstract class SubscriberRepositoryTestBase extends TestCase
{
    protected SubscriberRepositoryInterface $subscriberRepository;

    protected function setUp(): void
    {
        $this->initialize();
    }

    abstract protected function initialize(): void;

    public function testFindById(): void
    {
        $endpoint = new Endpoint('prime');
        $expirationTime = new ExpirationTime();
        $keys = new Keys("auth123", "encrypt123");

        $publisher = new Publisher("www.exemple.com");
        $subscriber1 = new Subscriber(
            $endpoint,
            $keys,
            $expirationTime,
            $publisher
        );
        $endpoint2 = new Endpoint('prime2');
        $keys2 = new Keys("123auth", "123encrypt");
        $subscriber2 = new Subscriber(
            $endpoint2,
            $keys2,
            $expirationTime,
            $publisher
        );

        $this->subscriberRepository->add($subscriber1);
        $found = $this->subscriberRepository->findById($endpoint);
        $this->subscriberRepository->add($subscriber2);
        $found2 = $this->subscriberRepository->findById($endpoint2);

        $this->assertInstanceOf(SubscriberRepositoryInterface::class, $this->subscriberRepository);
        $this->assertNotNull($found);
        $this->assertInstanceOf(Subscriber::class, $found);
        $this->assertEquals("prime", $found->getEndpoint());
        $this->assertNotNull($found2);
        $this->assertFalse($found->getEndpoint()->equals($found2->getEndpoint()));
    }

    public function testFindByIdException(): void
    {
        $this->expectException(SubscriberNotFoundException::class);
        $this->expectExceptionMessage("Error can't find the endpoint prime54845");
        $this->subscriberRepository->findById(new Endpoint("prime54845"));
    }
}
