<?php

namespace Notifications\Domain\Entity\Notification;

class NotificationAddress
{
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    private array $navigatorAddress;

    /**
     * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $address
     */
    public function __construct(array $address)
    {
        $this->navigatorAddress = $address;
    }

    /** @return array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    public function getAddress(): array
    {
        return $this->navigatorAddress;
    }
}
