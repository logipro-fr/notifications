<?php

namespace Notifications\Tests\Domain\Publisher;

use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Domain\Publisher\NotificationAddress;
use Notifications\Domain\Publisher\Publisher;
use Notifications\Domain\Subscriber;
use Notifications\Infrastructure\VapidGenerator;
use Notifications\Tests\Domain\KeyGenFake;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PublisherTest extends TestCase
{
    private const URL_1 = "https://example.fr";
    private const URL_2 = "https://nextsign.fr";

    protected KeyGeneratorStrategy $generatorFake;
    protected KeyGeneratorStrategy $generatorVAPID;

    protected function setUp(): void
    {
        $this->generatorFake = new KeyGenFake();
        $this->generatorVAPID = new VapidGenerator();
    }

    public function testCreateAnUniquePublisher(): void
    {
        $notificationAddress = new NotificationAddress(self::URL_1);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorFake, $notificationAddress->getAddress());
        $resultURL = $notificationSystem->getNotificationAddress()->getAddress();
        $this->assertEquals(self::URL_1, $resultURL);
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($key1 = $notificationSystem->getPublicKey());

        $notificationAddress2 = new NotificationAddress(self::URL_2);
        $ns = new Publisher(self::URL_2, $this->generatorFake, $notificationAddress2->getAddress());
        $this->assertNotEquals($key1, $ns->getPublicKey());
    }

    public function testCreateApplication(): void
    {
        $notificationAddress = new NotificationAddress("ClashOfClans");
        $notificationSystem = new Publisher("ClashOfClans", $this->generatorFake, $notificationAddress->getAddress());
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
    }

    public function testVapid(): void
    {
        $notificationAddress = new NotificationAddress(self::URL_1);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($notificationSystem->getPublicKey());
    }

    public function testTarget(): void
    {
        $notificationAddress = new NotificationAddress(self::URL_1);
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $this->assertEquals(self::URL_1, $notificationSystem->getTargetName());
    }

    public function testAddSubscriber(): void
    {
        $notificationAddress = new NotificationAddress(self::URL_1);
        $publisher = new PublisherFake(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);
        $this->assertEquals(1, $publisher->countSubscriber());

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);
        $this->assertEquals(2, $publisher->countSubscriber());
    }

    public function testDeleteSubscriber(): void
    {
        $notificationAddress = new NotificationAddress(self::URL_1);
        $publisher = new PublisherFake(self::URL_1, $this->generatorVAPID, $notificationAddress->getAddress());
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);
        $this->assertEquals(1, $publisher->countSubscriber());
        $publisher->unsubscribe($subscriber);
        $this->assertEquals(0, $publisher->countSubscriber());
    }
}
