<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Infrastructure\Subscriber\RequestSubscriber;
use PHPUnit\Framework\TestCase;

class SubscriberStructureTest extends TestCase
{
    protected RequestSubscriber $requestSubscriber;

    protected function setUp(): void
    {
        $this->requestSubscriber = new RequestSubscriber();
    }

    public function testPostRequestCreatesSubscription(): void
    {

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $headers = [];
        $headerCallback = function ($header) use (&$headers) {
            $headers[] = $header;
        };
        ob_start();
        $this->requestSubscriber->verifyHeader($headerCallback);
        ob_end_clean();

        $this->assertContains('Location: https://push.example.net/push/LBhhw0OohO-Wl4Oi971UG', $headers);
        $this->assertContains('Link: </push/LBhhw0OohO-Wl4Oi971UG>; rel="urn:ietf:params:push"', $headers);
        $this->assertContains('</subscription-set/LBhhw0OohO-Wl4Oi971UG>; rel="urn:ietf:params:push:set"', $headers);
        $this->assertEquals("Subscription created successfully", $this->requestSubscriber->requestSub());
        $this->assertEquals(201, http_response_code());
    }

    public function testGetRequestReturnsMethodNotAllowed(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $headers = [];
        $headerCallback = function ($header) use (&$headers) {
            $headers[] = $header;
        };
        ob_start();
        $this->requestSubscriber->verifyHeader($headerCallback);
        ob_end_clean();

        $this->assertContains('HTTP/1.1 405 Method Not Allowed', $headers);
        $this->assertEquals("Method Not Allowed", $this->requestSubscriber->requestSub());
        $this->assertEquals(405, http_response_code());
    }
}
