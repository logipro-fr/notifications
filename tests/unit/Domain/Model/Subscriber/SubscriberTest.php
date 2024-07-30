<?php

namespace Notifications\Tests\Domain\Model\Subscriber;

use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
use Notifications\Domain\Model\Subscriber\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    public function testGetSubscriberValues(): void
    {
        $endpoint = new Endpoint("www.nextsign.fr");
        $keys = new Keys("1234", "5678");
        $expirationTime = new ExpirationTime("");
        $publisher = $this->createMock(Publisher::class);

        $subscriber = new Subscriber(
            $endpoint,
            $keys,
            $expirationTime,
            $publisher
        );
        $this->assertSame($endpoint, $subscriber->getEndpoint());
        $this->assertSame($keys, $subscriber->getKeys());
        $this->assertSame($publisher, $subscriber->getPublisher());
    }
}
