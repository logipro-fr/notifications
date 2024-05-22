<?php

namespace Notifications\Tests\Application\Service\Subscription;

use Minishlink\WebPush\Subscription as WebPushSubscription;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Tests\Domain\Services\KeyGenFake;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    private const SUB_ID_FOR_NEXT_SIGN = [
        "endpoint" => "nextsign.fr",
        "expirationTime" => null,
        "keys" => [
            "auth" => "",
            "p256dh" => ""
        ]
    ];
    private const SUB_ID_FOR_LE_MONDE = [
        "endpoint" => "https://lemonde.fr",
        "expirationTime" => null,
        "keys" => [
            "auth" => "",
            "p256dh" => ""
        ]
    ];
    public function testExectute(): void
    {
        $keyEngine = new KeyGenFake();
        $request = new SubscriptionRequest(self::SUB_ID_FOR_NEXT_SIGN);
        $service = new Subscription($keyEngine);
        $service->execute($request);
        $response = $service->getResponse();
        $this->assertIsString($response->tokenSubscriber);

        $tokenFirstCall = $response->tokenSubscriber;
        $this->assertEquals($tokenFirstCall, $service->getResponse()->tokenSubscriber);

        $service->execute(new SubscriptionRequest(self::SUB_ID_FOR_LE_MONDE));

        $response = $service->getResponse();
        $this->assertNotEquals($tokenFirstCall, $response->tokenSubscriber);
    }

    public function testConstructFull(): void
    {
        $subscription = new WebPushSubscription("http://toto.com", "publicKey", "authToken", "aes128gcm");
        $this->assertEquals("http://toto.com", $subscription->getEndpoint());
        $this->assertEquals("publicKey", $subscription->getPublicKey());
        $this->assertEquals("authToken", $subscription->getAuthToken());
        $this->assertEquals("aes128gcm", $subscription->getContentEncoding());
    }
}
