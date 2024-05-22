<?php

namespace Notifications\Tests\Domain\Entity\Publisher;

use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Keys\VapidGenerator;
use Notifications\Tests\Domain\Services\KeyGenFake;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PublisherTest extends TestCase
{
    private const URL_1 = "https://example.fr";
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    private const SUB1_ID = [
        "endpoint" => "https://example.fr",
        "expirationTime" => null,
        "keys" => [
            "auth" => "",
            "p256dh" => ""
        ]
    ];
    private const URL_2 = "https://fakeoutputadresse";
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    private const SUB2_ID = [
        "endpoint" => "https://fakeoutputadresse",
        "expirationTime" => null,
        "keys" => [
            "auth" => "",
            "p256dh" => ""
        ]
    ];

    protected KeyGeneratorStrategy $generatorFake;
    protected KeyGeneratorStrategy $generatorVAPID;

    protected function setUp(): void
    {
        $this->generatorFake = new KeyGenFake();
        $this->generatorVAPID = new VapidGenerator();
    }

    public function testCreateAnUniquePublisher(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorFake, $notificationAddress->getAddress());
        $resultURL = $notificationSystem->getNotificationAddress()->getAddress();
        $this->assertEquals(self::SUB1_ID, $resultURL);
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($key1 = $notificationSystem->getPublicKey());

        $notificationAddress2 = new NotificationAddress(self::SUB2_ID);
        $ns = new Publisher(self::URL_2, $this->generatorFake, $notificationAddress2->getAddress());
        $this->assertNotEquals($key1, $ns->getPublicKey());
    }

    public function testVapid(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($notificationSystem->getPublicKey());
    }

    public function testTarget(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $this->assertEquals(self::URL_1, $notificationSystem->getTargetName());
    }

    public function testAddSubscriber(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $publisher = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);

        $subscribers = $property->getValue($publisher);
        $this->assertIsArray($subscribers);
        $this->assertEquals(1, count($subscribers));

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);

        $subscribers = $property->getValue($publisher);
        $this->assertIsArray($subscribers);
        $this->assertEquals(2, count($subscribers));
    }

    public function testDeleteSubscriber(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $publisher = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);

        $subscribers = $property->getValue($publisher);
        $this->assertIsArray($subscribers);
        $this->assertEquals(1, count($subscribers));

        $publisher->unsubscribe($subscriber);

        $subscribers = $property->getValue($publisher);
        $this->assertIsArray($subscribers);
        $this->assertEquals(0, count($subscribers));
        $this->assertEmpty($publisher->removePublicKey());
    }

    public function testUnsubscribeCallsRemovePublicKey(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $keyGenerator = $this->generatorVAPID;

        $publisher = new Publisher(self::URL_1, $keyGenerator, $notificationAddress->getAddress());

        $subscriber = $this->createMock(Subscriber::class);
        $publisher->subscribe($subscriber);

        $this->assertEquals("KeyRemoved", $publisher->removePublicKey());
    }

    public function testUnsubscribeNonExistingSubscriber(): void
    {
        $notificationAddress = new NotificationAddress(self::SUB1_ID);
        $keyGenerator = $this->generatorVAPID;

        $publisher = new Publisher(self::URL_1, $keyGenerator, $notificationAddress->getAddress());

        $subscriber1 = $this->createMock(Subscriber::class);

        $publisher->subscribe($subscriber1);
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);
        $nonExistingSubscriber = $this->createMock(Subscriber::class);

        $publisher->unsubscribe($nonExistingSubscriber);

        $subscribers = $property->getValue($publisher);
        $this->assertIsArray($subscribers);
        $this->assertEquals(1, count($subscribers));
    }
}
