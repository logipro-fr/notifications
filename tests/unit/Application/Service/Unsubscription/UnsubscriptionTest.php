<?php

namespace Notifications\Tests\Application\Service\Subscription;

use Notifications\Application\Service\Unsubscription\Unsubscription;
use Notifications\Application\Service\Unsubscription\UnsubscriptionRequest;
use Notifications\Tests\Domain\KeyGenFake;
use PHPUnit\Framework\TestCase;

class UnsubscriptionTest extends TestCase
{
    private const NAME = "https://nextsign.fr";

    protected KeyGenFake $generator;
    /** @var array<mixed> */
    protected array $generated;
    /** @var array<mixed> */
    protected array $fakeId;

    protected function setUp(): void
    {

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
    public function testExectute(): void
    {
        $service = new Unsubscription($this->generator);
        $request = new UnsubscriptionRequest(self::NAME, $this->fakeId);
        $service->execute($request);
        $response = $service->getResponse();
        $anwser = $response->message;
        $this->assertIsString($anwser);
        $this->assertEquals($anwser, $service->getResponse()->message);
    }
}
