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
        $request = new Request([], [], [], [], [], [], $jsonContent);

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
        $content = $response->getContent();
        if ($content !== false) {
            $responseContent = json_decode($content, true);
        } else {
            throw new \Exception('Failed to get content from response.');
        }
        if ($responseContent === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
        }
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
        $request = new Request([], [], [], [], [], [], $jsonContent);

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
        $content = $response->getContent();
        if ($content !== false) {
            $responseContent = json_decode($content, true);
        } else {
            throw new \Exception('Failed to get content from response.');
        }
        if ($responseContent === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
        }
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

        $jsonContent = json_encode($requestData);
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }
        $request = new Request([], [], [], [], [], [], $jsonContent);

        $response = $this->controller->sendNotification($request);
        $content = $response->getContent();
        if ($content !== false) {
            $responseContent = json_decode($content, true);
        } else {
            throw new \Exception('Failed to get content from response.');
        }
        if ($responseContent === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
        }
        if (is_array($responseContent) && isset($responseContent['data'][0])) {
            $responseContent['data'][0] = json_decode($responseContent['data'][0], true);
        }

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $actualJson = $response->getContent();
        $expectedResponse = [
            'success' => false,
            'ErrorCode' => "Invalid notification data",
            'message' => "An error occurred"
        ];
        $expectedJson = json_encode($expectedResponse);
        if ($expectedJson === false || $actualJson === false) {
            throw new \RuntimeException('Failed to encode expected JSON');
        }
        $this->assertJsonStringEqualsJsonString($expectedJson, $actualJson);
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

        $jsonContent = json_encode($requestData);
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }
        $request = new Request([], [], [], [], [], [], $jsonContent);

        $response = $this->controller->sendNotification($request);
        $content = $response->getContent();
        if ($content !== false) {
            $responseContent = json_decode($content, true);
        } else {
            throw new \Exception('Failed to get content from response.');
        }
        if ($responseContent === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
        }
        if (is_array($responseContent) && isset($responseContent['data'][0])) {
            $responseContent['data'][0] = json_decode($responseContent['data'][0], true);
        }

        $this->webPushMock->expects($this->once())
            ->method('sendOneNotification')
            ->willThrowException(new \Exception('Push service error'));

        $response = $this->controller->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $actualJson = $response->getContent();
        $expectedResponse = [
            'success' => false,
            'ErrorCode' => 'Push service error',
            'message' => "An error occurred"
        ];
        $expectedJson = json_encode($expectedResponse);
        if ($expectedJson === false || $actualJson === false) {
            throw new \RuntimeException('Failed to encode expected JSON');
        }
        $this->assertJsonStringEqualsJsonString($expectedJson, $actualJson);
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

        $jsonContent = json_encode($requestData);
        if ($jsonContent === false) {
            throw new \RuntimeException('Failed to encode JSON');
        }
        $request = new Request([], [], [], [], [], [], $jsonContent);

        $response = $this->controller->sendNotification($request);
        $content = $response->getContent();
        if ($content !== false) {
            $responseContent = json_decode($content, true);
        } else {
            throw new \Exception('Failed to get content from response.');
        }
        if ($responseContent === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Failed to decode JSON: ' . json_last_error_msg());
        }
        if (is_array($responseContent) && isset($responseContent['data'][0])) {
            $responseContent['data'][0] = json_decode($responseContent['data'][0], true);
        }

        $response = $this->controller->sendNotification($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $actualJson = $response->getContent();
        $expectedResponse = [
            'success' => false,
            'ErrorCode' => "Invalid subscription data",
            'message' => "An error occurred"
        ];
        $expectedJson = json_encode($expectedResponse);
        if ($expectedJson === false || $actualJson === false) {
            throw new \RuntimeException('Failed to encode expected JSON');
        }
        $this->assertJsonStringEqualsJsonString($expectedJson, $actualJson);
    }
}
