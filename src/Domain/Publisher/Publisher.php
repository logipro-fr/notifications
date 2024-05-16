<?php

namespace Notifications\Domain\Publisher;

use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Domain\Subscriber;

class Publisher
{
    private mixed $publicKey;
    private string $name;
    private NotificationAddress $notificationAddress; 

    /** @var array<Subscriber> */
    protected array $subscribers = [];

    public function __construct(string $name, private KeyGeneratorStrategy $keyGenerator, string $notificationAddress) 
    {
        $this->name = $name;
        $this->notificationAddress = new NotificationAddress($notificationAddress);
        $keys = $this->keyGenerator->generateACoupleOfKey();
        $this->publicKey = $keys['publicKey'];
    }

    public function getPublicKey(): mixed
    {
        return $this->publicKey;
    }

    public function getTargetName(): string
    {
        return $this->name;
    }

    public function getNotificationAddress(): NotificationAddress
    {
        return $this->notificationAddress;
    }
    
    public function subscribe(Subscriber $subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }

    public function unsubscribe(Subscriber $subscriber): void
    {
        $index = array_search($subscriber, $this->subscribers, true);
        if ($index !== false) {
            unset($this->subscribers[$index]);
        }
        $this->removePublicKey();
    }

    private function removePublicKey(): string
    {
        $this->publicKey = "";
        return "KeyRemoved";
    }
}
