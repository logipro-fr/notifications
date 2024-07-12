<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\AuthKey;
use Notifications\Domain\Entity\Subscriber\EncryptKey;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private Endpoint $endpoint;
    private Keys $keys;
    private ExpirationTime $expirationTime;
    private Publisher $publisher;

    private Subscriber $subscriber;

    protected function setUp(): void
    {
        $this->endpoint = $this->createMock(Endpoint::class);
        $this->keys = $this->createMock(Keys::class);
        $this->expirationTime = $this->createMock(ExpirationTime::class);
        $this->publisher = $this->createMock(Publisher::class);

        $this->subscriber = new Subscriber(
            $this->endpoint,
            $this->keys,
            $this->expirationTime,
            $this->publisher
        );
    }

    public function testGetEndpoint(): void
    {
        $this->assertSame($this->endpoint, $this->subscriber->getEndpoint());
    }

    public function testGetKeys(): void
    {
        $this->assertSame($this->keys, $this->subscriber->getKeys());
    }

    public function testPublisher(): void
    {
        $this->assertSame($this->publisher, $this->subscriber->getPublisher());
    }
}
