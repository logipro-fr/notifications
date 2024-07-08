<?php

namespace Notifications\Tests\Infrastructure\Persistance\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;
use Notifications\Domain\Services\KeyGeneratorStrategy;
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
        $keys = new Keys();
        $publisher = new Publisher("www.exemple.com");
        $subscriber1 = new Subscriber(
            $endpoint,
            $keys,
            $expirationTime,
            $publisher
        );
        $endpoint2 = new Endpoint('prime2');
        $keys2 = new Keys();
        $subscriber2 = new Subscriber(
            $endpoint2,
            $keys2,
            $expirationTime,
            $publisher
        );

        $this->subscriberRepository->add($subscriber1);
        $found = $this->subscriberRepository->findById(new Endpoint("prime"));
        $this->subscriberRepository->add($subscriber2);
        $found2 = $this->subscriberRepository->findById(new Endpoint("prime2"));

        $this->assertInstanceOf(SubscriberRepositoryInterface::class, $this->subscriberRepository);
        $this->assertInstanceOf(Subscriber::class, $found);
        $this->assertEquals("prime", $found->getEndpoint());
        $this->assertFalse($found->getEndpoint()->equals($found2->getEndpoint()));
    }

    public function testFindByIdException(): void
    {
        $this->expectException(SubscriberNotFoundException::class);
        $this->expectExceptionMessage("Error can't find the endpoint prime54845");
        $this->subscriberRepository->findById(new Endpoint("prime54845"));
    }
}
