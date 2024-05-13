<?php

namespace Notifications\Tests\Domain;

use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Domain\Publisher;
use Notifications\Domain\Subscriber;
use Notifications\Infrastructure\VapidGenerator;
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

    public function testCreateWebsite(): void
    {
        $notificationSystem = new Publisher(self::URL_1, $this->generatorFake);
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($key1 = $notificationSystem->getPublicKey());

        $ns = new Publisher(self::URL_2, $this->generatorFake);
        $this->assertNotEquals($key1, $ns->getPublicKey());
    }

    public function testCreateApplication(): void
    {
        $notificationSystem = new Publisher("ClashOfClans", $this->generatorFake);
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
    }

    public function testVapid(): void
    {
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID);
        $this->assertInstanceOf(Publisher::class, $notificationSystem);
        $this->assertIsString($notificationSystem->getPublicKey());
    }

    public function testTarget(): void
    {
        $notificationSystem = new Publisher(self::URL_1, $this->generatorVAPID);
        $this->assertEquals(self::URL_1, $notificationSystem->getTargetName());
    }

    public function testAddSubscriber(): void
    {
        $publisher = new PublisherFake(self::URL_1, $this->generatorVAPID);
        $reflectionPublisher = new ReflectionClass($publisher);
        $property = $reflectionPublisher->getProperty("subscribers");
        $property->setAccessible(true);
        $subscribers = $property->getValue($publisher);

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);
        $this->assertEquals(1, $publisher->countSubscriber());

        $subscriber = new Subscriber();
        $publisher->subscribe($subscriber);
        $this->assertEquals(2, $publisher->countSubscriber());
    }
}
