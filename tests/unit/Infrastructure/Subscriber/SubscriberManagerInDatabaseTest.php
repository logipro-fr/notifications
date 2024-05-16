<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Domain\Subscriber;
use Notifications\Infrastructure\Subscriber\SubscriberManagerInDatabase;
use Notifications\Tests\Domain\KeyGenFake;
use PHPUnit\Framework\TestCase;

class SubscriberManagerInDatabaseTest extends TestCase{
    private const URL_TARGET = "https://nextsign.fr";

    protected SubscriberManagerInDatabase $subscriber;
    protected KeyGenFake $generator;
    /** @var array<mixed> */
    protected array $generated;
    /** @var array<mixed> */
    protected array $fakeId;

    protected function setUp(): void
    {
        $this->subscriber = new SubscriberManagerInDatabase();
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
    }
    
    public function testRegisterMe(): void
    {
        $registration = $this->subscriber->registerSubInDatabase(self::URL_TARGET, $this->generated);
        $this->assertEquals("registered", $registration);
    }
}