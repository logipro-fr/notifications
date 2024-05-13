<?php

namespace Notifications\Tests\Domain;

use Notifications\Domain\Publisher;
use Notifications\Domain\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private const URL_TARGET = "https://nextsign.fr";

    public function testCreate(): void
    {
        $subscriber = new Subscriber();
        $generator = new KeyGenFake();
        $state = $subscriber->subscribe(new Publisher(self::URL_TARGET, $generator));
        $this->assertEquals("subscribed", $state);
    }

    //public function testRegisterMe(): void
    //{
    //    $subscriber = new Subscriber();
    //    $generator = (new KeyGenFake())->generateACoupleOfKey();
    //    $registration = $subscriber->registerSubInDatabase(self::URL_TARGET, $generator);
    //    $this->assertEquals("registered", $registration);
    //}
}
