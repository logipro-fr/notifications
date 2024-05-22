<?php

namespace Notifications\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;

class Subscriber
{
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    protected array $subscriberId;

    /**
     * @param Publisher $name
     * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $userAddress
     * @return string
     */
    public function subscribe(Publisher $name, array $userAddress): string
    {
        $message = "subscribed";
        $this->subscriberId = $userAddress;
        return $message;
    }

    /**
     * @return array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}}
     */
    public function getSubscriberId(): array
    {
        return $this->subscriberId;
    }
}
