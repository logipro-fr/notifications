<?php

namespace Notifications\Domain\Entity\Publisher;

use Notifications\Domain\Entity\Notification\NotificationAddress;
use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Domain\Entity\Subscriber\Subscriber;

class Publisher
{
    private string $publicKey;
    private string $name;
    private NotificationAddress $notificationAddress;

    /** @var array<Subscriber> */
    protected array $subscribers = [];

    /**
    * @param array{
    *     endpoint: string,
    *     expirationTime: ?string,
    *     keys: array{auth: string, p256dh: string}
    * } $notificationAddress
    */
    public function __construct(string $name, private KeyGeneratorStrategy $keyGenerator, array $notificationAddress)
    {
        $this->name = $name;
        $this->notificationAddress = new NotificationAddress($notificationAddress);
        $keys = $this->keyGenerator->generateACoupleOfKey();
        $this->publicKey = $keys['publicKey'];
    }

    public function getPublicKey(): string
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
            $this->removePublicKey();
        }
    }

    public function removePublicKey(): string
    {
        if ($this->publicKey !== "") {
            $this->publicKey = "";
            return "KeyRemoved";
        }
        return "";
    }
}
