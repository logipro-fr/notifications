<?php

namespace Notifications\Tests\Domain;

use Minishlink\WebPush\Notification;
use Notifications\Domain\Publisher\NotificationAddress;
use Notifications\Domain\Publisher\Publisher;
use Notifications\Domain\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private const URL_TARGET = "https://nextsign.fr";

    protected Publisher $publisher;
    protected Subscriber $subscriber;
    protected KeyGenFake $generator;
    protected NotificationAddress $notificationAddress;
    /** @var array<mixed> */
    protected array $generated;
    /** @var array<mixed> */
    protected array $fakeId;


    protected function setUp(): void
    {  
        $this->subscriber = new Subscriber();
        $this->generator = new KeyGenFake();
        $this->generated = $this->generator->generateACoupleOfKey();
        $this->notificationAddress = new NotificationAddress(self::URL_TARGET);
        
        $this->publisher = new Publisher(self::URL_TARGET, $this->generator, $this->notificationAddress->getAddress());
        $this->fakeId = [
            "endpoint" => "https://fakeoutputadresse",
            "expirationTime" => null,
            "keys" => [
                "auth" => "",
                "p256dh" => ""
            ]
        ];
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
