<?php

namespace Notifications\Tests\Domain\Entity\Subscriber;

use Minishlink\WebPush\Notification;
use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Tests\Domain\Services\KeyGenFake;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private const URL_TARGET = "https://fakeoutputadresse";

    protected Publisher $publisher;
    protected Subscriber $subscriber;
    protected KeyGenFake $generator;
    protected NotificationAddress $notificationAddress;

    /** @var array<string> */
    protected array $generated;

    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    protected array $fakeId;


    protected function setUp(): void
    {
        $this->subscriber = new Subscriber();
        $this->generator = new KeyGenFake();
        $this->generated = $this->generator->generateACoupleOfKey();

        $this->fakeId = [
            "endpoint" => "https://fakeoutputadresse",
            "expirationTime" => null,
            "keys" => [
                "auth" => "",
                "p256dh" => ""
            ]
        ];
        $this->notificationAddress = new NotificationAddress($this->fakeId);
        $this->publisher = new Publisher(self::URL_TARGET, $this->generator, $this->notificationAddress->getAddress());
    }

    public function testCreate(): void
    {
        $state = $this->subscriber->subscribe($this->publisher, $this->fakeId);
        $this->assertEquals("subscribed", $state);
    }

    public function testGetId(): void
    {
        $this->subscriber->subscribe($this->publisher, $this->fakeId);
        $idObtained = $this->subscriber->getSubscriberId();
        $this->assertEquals($this->fakeId, $idObtained);
    }
}
