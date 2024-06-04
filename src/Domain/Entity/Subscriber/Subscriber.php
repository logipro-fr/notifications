<?php

namespace Notifications\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Publisher\Publisher;

class Subscriber
{
    /** @var array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} */
    protected array $subscriberId;
    private string $endpoint;
    /** @var array{auth: string, p256dh: string} */
    private array $keys;
    private ?string $expirationTime;


    /**
     * @param Publisher $name
     * @param array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}} $userAddress
     * @return string
     */
    public function subscribe(Publisher $name, array $userAddress): string
    {
        $this->subscriberId = $userAddress;
        $this->endpoint = $userAddress['endpoint'];
        $this->keys = $userAddress['keys'];
        $this->expirationTime = $userAddress['expirationTime'];

        return "subscribed";
    }

    /**
     * @return array{endpoint: string, expirationTime: ?string, keys: array{auth: string, p256dh: string}}
     */
    public function getSubscriberId(): array
    {
        return $this->subscriberId;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return array{auth: string, p256dh: string}
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    public function getExpirationTime(): ?string
    {
        return $this->expirationTime;
    }

    /**
     * @return array{auth: string, p256dh: string}
     */
    public function getDecodedKeys(): array
    {
        return [
            'auth' => isset($this->keys['auth']) ? $this->base64UrlDecode($this->keys['auth']) : '',
            'p256dh' => isset($this->keys['p256dh']) ? $this->base64UrlDecode($this->keys['p256dh']) : '',
        ];
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
