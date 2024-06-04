<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Tests\Domain\Services\KeyGenFake;

class SubscriberController
{
    private SubscriberManager $subscriberManager;

    public function __construct(SubscriberManager $subscriberManager)
    {
        $this->subscriberManager = $subscriberManager;
    }

    /**
     * @param array{endpoint: string, expirationTime: ?string,
     * keys: array{auth: string, p256dh: string}, notificationAddress: array<string, string>} $data
     * @return string
     */
    public function addSubscriber(array $data): string
    {
        $subscriber = new Subscriber();
        $keyGenerator = new KeyGenFake();

        // Construire correctement le tableau pour Publisher
        $notificationAddress = [
            'endpoint' => $data['endpoint'],
            'expirationTime' => $data['expirationTime'],
            'keys' => $data['keys'],
        ];

        $publisher = new Publisher('PublisherName', $keyGenerator, $notificationAddress);
        $subscriber->subscribe($publisher, $data);
        $this->subscriberManager->addSubscriber($subscriber);
        return "Subscriber added";
    }

    public function getSubscribers(): string
    {
        $subscribers = $this->subscriberManager->getSubscribers();
        $jsonResult = json_encode($subscribers);
        if ($jsonResult === false) {
            return "Error encoding JSON";
        }
        return $jsonResult;
    }
}
