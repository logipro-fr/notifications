<?php

namespace Notifications\Tests\Infrastructure\Subscriber;

use PHPUnit\Framework\TestCase;
use Notifications\Infrastructure\Subscriber\InMemorySubscriberRepository;
use Notifications\Infrastructure\Subscriber\SubscriberManager;
use Notifications\Domain\Service\KeyGeneratorStrategy;
use Notifications\Infrastructure\Subscriber\SubscriberController;

class SubscriberControllerTest extends TestCase
{
    private SubscriberController $subscriberController;

    protected function setUp(): void
    {
        $repository = new InMemorySubscriberRepository();
        $manager = new SubscriberManager($repository);
        $this->subscriberController = new SubscriberController($manager);
    }

    public function testAddSubscriber(): void
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

        $result = $this->subscriberController->addSubscriber($data);

        $this->assertEquals('Subscriber added', $result);
    }

    public function testGetSubscribers(): void
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

        $this->subscriberController->addSubscriber($data);
        $result = $this->subscriberController->getSubscribers();

        $this->assertJson($result);
        $decodedResult = json_decode($result, true);
        $this->assertIsArray($decodedResult);
        $this->assertCount(1, $decodedResult);
    }
}
