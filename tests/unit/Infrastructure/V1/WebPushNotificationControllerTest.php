<?php

namespace Notifications\Tests\Infrastructure\V1;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription as WebPushSubscription;
use Notifications\Infrastructure\Api\V1\WebPushNotificationController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WebPushNotificationControllerTest extends TestCase
{
    private MockObject $webPushMock;
    private WebPushNotificationController $controller;

    protected function setUp(): void
    {
        $this->webPushMock = $this->createMock(WebPush::class);
        $this->controller = new WebPushNotificationController();
        $reflection = new \ReflectionClass($this->controller);
        $property = $reflection->getProperty('webPush');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->webPushMock);
    }

    public function testSendCompleteNotificationSuccess(): void
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

        $jsonContent = json_encode($requestData);
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }
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
        if (is_array($responseContent) && isset($responseContent['data'][0])) {
            $responseContent['data'][0] = json_decode($responseContent['data'][0], true);
        }

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

    public function testSendPartialNotificationWithoutIcon(): void
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
                'url' => 'https://example.com'
            ]
        ];
        $jsonContent = json_encode($requestData);
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $this->webPushMock->expects($this->once())
            ->method('sendOneNotification')
            ->with(
                $this->isInstanceOf(WebPushSubscription::class),
                json_encode([
                    'title' => 'Test Title',
                    'body' => 'Test Description',
                    'url' => 'https://example.com'
                ])
            );

        $response = $this->controller->sendNotification($request);

        $responseContent = json_decode($response->getContent(), true);
        if (is_array($responseContent) && isset($responseContent['data'][0])) {
            $responseContent['data'][0] = json_decode($responseContent['data'][0], true);
        }

        $expectedResponse = [
            'success' => true,
            'ErrorCode' => "",
            'data' => [[
                'title' => 'Test Title',
                'body' => 'Test Description',
                'url' => 'https://example.com'
            ]],
            'message' => ""
        ];

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $responseContent);
    }

    public function testSendNotificationWithMissingTitleOrDescription(): void
    {
        $requestData = [
            'endpoint' => 'https://example.com/endpoint',
            'keys' => [
                'auth' => 'auth_key',
                'p256dh' => 'p256dh_key'
            ],
            'notification' => [
                'title' => '',
                'description' => 'Test Description'
            ]
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $response = $this->controller->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            'success' => false,
            'ErrorCode' => "Invalid notification data",
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

    public function testSendNotificationWithInvalidSubscriptionData(): void
    {
        $requestData = [
            'keys' => [
                'auth' => 'auth_key',
                'p256dh' => 'p256dh_key'
            ],
            'notification' => [
                'title' => 'Test Title',
                'description' => 'Test Description'
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
}
