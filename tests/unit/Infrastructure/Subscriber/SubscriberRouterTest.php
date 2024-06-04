<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use PHPUnit\Framework\TestCase;
use Notifications\Infrastructure\Subscriber\SubscriberManager;
use Notifications\Infrastructure\Subscriber\InMemorySubscriberRepository;
use Notifications\Domain\Service\KeyGeneratorStrategy;
use Notifications\Infrastructure\Subscriber\SubscriberController;
use Notifications\Infrastructure\Subscriber\SubscriberRouter;

class SubscriberRouterTest extends TestCase
{
    private SubscriberRouter $router;
    private SubscriberController $subscriberController;

    protected function setUp(): void
    {
        $repository = new InMemorySubscriberRepository();
        $manager = new SubscriberManager($repository);
        $this->subscriberController = new SubscriberController($manager);
        $this->router = new SubscriberRouter($this->subscriberController);
    }

    public function testRouteAddSubscriber(): void
    {
        $data = [
            'endpoint' => 'https://example.com',
            'expirationTime' => null,
            'keys' => [
                'auth' => 'authKey',
                'p256dh' => 'p256dhKey',
            ],
            'notificationAddress' => [
                'someAddressKey' => 'someAddressValue'
            ]
        ];

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $response = $this->router->routeRequest('/addSubscriber', 'POST', $data);
        $this->assertEquals('Subscriber added', $response);
    }

    public function testRouteGetSubscribers(): void
    {
        $data = [
            'endpoint' => 'https://example.com',
            'expirationTime' => null,
            'keys' => [
                'auth' => 'authKey',
                'p256dh' => 'p256dhKey',
            ],
            'notificationAddress' => [
                'someAddressKey' => 'someAddressValue'
            ]
        ];

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->routeRequest('/getSubscribers', 'GET', $data);
        $this->assertJson($response);
    }

    public function testRouteNotFound(): void
    {
        $data = [
            'endpoint' => '',
            'expirationTime' => null,
            'keys' => [
                'auth' => '',
                'p256dh' => '',
            ],
            'notificationAddress' => []
        ];

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->routeRequest('/invalidRoute', 'GET', $data);
        $this->assertEquals('404 Not Found', $response);
    }
}
