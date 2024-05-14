<?php

namespace Notifications\Tests\Domain;

use Notifications\Domain\Exceptions\BadDataClassException;
use Notifications\Domain\Publisher;
use Notifications\Domain\Subscriber;
use PHPUnit\Framework\TestCase;

class SubscriberTest extends TestCase
{
    private const URL_TARGET = "https://nextsign.fr";

    protected Subscriber $subscriber;
    protected KeyGenFake $generator;
    /** @var array<mixed> */
    protected array $generated;


    protected function setUp(): void
    {
        $this->subscriber = new Subscriber();
        $this->generator = new KeyGenFake();
        $this->generated = $this->generator->generateACoupleOfKey();
    }

    public function testCreate(): void
    {

        $state = $this->subscriber->subscribe(new Publisher(self::URL_TARGET, $this->generator));
        $this->assertEquals("subscribed", $state);
    }

    //public function testRegisterMe(): void
    //{
    //    $registration = $this->subscriber->registerSubInDatabase(self::URL_TARGET, $this->generated);
    //    $this->assertEquals("registered", $registration);
    //}
}
