<?php

namespace Notifications\Tests\Infrastructure\V1;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;
use Notifications\Infrastructure\Api\V1\WebPushNotificationController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Notifications\Domain\Model\Notification\Notification;
use Notifications\Domain\Model\Notification\Title;
use Notifications\Domain\Model\Notification\Description;
use Notifications\Domain\Model\Notification\Icon;
use Notifications\Domain\Model\Notification\Action;

class WebPushNotificationControllerTest extends TestCase
{
    private $webPushMock;
    private $controller;

    protected function setUp(): void
    {
        $this->webPushMock = $this->createMock(WebPush::class);
        $this->controller = new WebPushNotificationController();
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('webPush');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->webPushMock);
    }

    public function testSendNotificationSuccess(): void
    {
        $requestData = [
            'endpoint' => 'https://example.com/endpoint',
            'keys' => [
                'auth' => 'auth_key',
                'p256dh' => 'p256dh_key'
            ],
            'notification' => [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'image' => 'https://example.com/icon.png',
                'url' => 'https://example.com'
            ]
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $this->webPushMock->expects($this->once())
            ->method('sendOneNotification')
            ->with(
                $this->isInstanceOf(WebPushSubscription::class),
                json_encode([
                    'title' => 'Test Title',
                    'body' => 'Test Description',
                    'icon' => 'https://example.com/icon.png',
                    'url' => 'https://example.com'
                ])
            );

        $response = $this->controller->sendNotification($request);

        $responseContent = json_decode($response->getContent(), true);
        $responseContent['data'][0] = json_decode($responseContent['data'][0], true);

        $expectedResponse = [
            'success' => true,
            'ErrorCode' => "",
            'data' => [[
                'title' => 'Test Title',
                'body' => 'Test Description',
                'icon' => 'https://example.com/icon.png',
                'url' => 'https://example.com'
            ]],
            'message' => ""
        ];

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $responseContent);
    }


    public function testSendNotificationWithInvalidData(): void
    {
        $requestData = [
            'endpoint' => 'https://example.com/endpoint',
            'keys' => [
                'auth' => '',
                'p256dh' => ''
            ]
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $response = $this->controller->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => false,
            'ErrorCode' => "Invalid subscription data",
            'message' => "An error occurred"
        ]), $response->getContent());
    }

    public function testSendNotificationExceptionHandling(): void
    {
        $requestData = [
            'endpoint' => 'https://example.com/endpoint',
            'keys' => [
                'auth' => 'auth_key',
                'p256dh' => 'p256dh_key'
            ],
            'notification' => [
                'title' => 'Test Title',
                'description' => 'Test Description',
                'image' => 'https://example.com/icon.png',
                'url' => 'https://example.com'
            ]
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $this->webPushMock->expects($this->once())
            ->method('sendOneNotification')
            ->willThrowException(new \Exception('Push service error'));

        $response = $this->controller->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => false,
            'ErrorCode' => 'Push service error',
            'message' => "An error occurred"
        ]), $response->getContent());
    }
}