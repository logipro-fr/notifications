<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Infrastructure\Subscriber\SubscriberView;
use PHPUnit\Framework\TestCase;

class SubscriberViewTest extends TestCase
{
    private const ANSWER_RENDER =
    '{"endpoint":"https:\/\/example.com","expirationTime":null,"keys":{"auth":"authKey","p256dh":"p256dhKey"}}';

    public function testRender(): void
    {
        $view = new SubscriberView();
        $data = [
            'endpoint' => 'https://example.com',
            'expirationTime' => null,
            'keys' => [
                'auth' => 'authKey',
                'p256dh' => 'p256dhKey',
            ],
        ];

        $json = $view->render($data);
        $this->assertJson($json);
        $this->assertEquals(self::ANSWER_RENDER, $json);
    }

    public function testRenderEmpty(): void
    {
        $view = new SubscriberView();
        $data = [
            'endpoint' => '',
            'expirationTime' => null,
            'keys' => [
                'auth' => '',
                'p256dh' => '',
            ],
        ];

        $json = $view->render($data);
        $this->assertJson($json);
        $this->assertEquals('{"endpoint":"","expirationTime":null,"keys":{"auth":"","p256dh":""}}', $json);
    }

    public function testRenderThrowsExceptionOnInvalidData(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'Failed to encode data to JSON: Malformed UTF-8 characters, possibly incorrectly encoded'
        );

        $view = new SubscriberView();
        // Invalid data that will cause json_encode to fail
        $data = [
            'endpoint' => "\xB1\x31",
            'expirationTime' => null,
            'keys' => [
                'auth' => 'authKey',
                'p256dh' => 'p256dhKey',
            ],
        ];

        $view->render($data);
    }
}
