<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Endpoint;
use Notifications\Domain\Entity\Subscriber\ExpirationTime;
use Notifications\Domain\Entity\Subscriber\Keys;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private Endpoint $endpoint;
    private Keys $publicKey;
    private ExpirationTime $expirationTime;
    private Publisher $publisher;

    protected function setUp(): void
    {
        $this->endpoint = $this->createMock(Endpoint::class);
        $this->publicKey = $this->createMock(Keys::class);
        $this->expirationTime = $this->createMock(ExpirationTime::class);
        $this->publisher = $this->createMock(Publisher::class);
    }

    public function testGetEndpoint(): void
    {
        $subscriber = new Subscriber(
            $this->endpoint,
            $this->publicKey,
            $this->expirationTime,
            $this->publisher
        );

        $this->assertSame($this->endpoint, $subscriber->getEndpoint());
    }

    public function testGetKeys(): void
    {
        $subscriber = new Subscriber(
            $this->endpoint,
            $this->publicKey,
            $this->expirationTime,
            $this->publisher
        );

        $this->assertSame($this->publicKey, $subscriber->getKeys());
    }
}
