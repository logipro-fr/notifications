<?php

namespace Notifications\Tests\Application\Service\Subscription;

use Minishlink\WebPush\Subscription as WebPushSubscription;
use Notifications\Application\Service\Subscription\Subscription;
use Notifications\Application\Service\Subscription\SubscriptionRequest;
use Notifications\Tests\Domain\KeyGenFake;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testExectute(): void
    {
        $keyEngine = new KeyGenFake();
        $request = new SubscriptionRequest("https://nextsign.fr");
        $service = new Subscription($keyEngine);
        $service->execute($request);
        $response = $service->getResponse();
        $this->assertIsString($response->tokenSubscriber);

        $tokenFirstCall = $response->tokenSubscriber;
        $this->assertEquals($tokenFirstCall, $service->getResponse()->tokenSubscriber);

        $service->execute(new SubscriptionRequest("https://lemonde.fr"));

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
