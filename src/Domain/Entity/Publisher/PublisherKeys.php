<?php

namespace Notifications\Domain\Entity\Publisher;

use Minishlink\WebPush\VAPID;
use Notifications\Domain\Services\KeyGeneratorStrategy;

class PublisherKeys implements KeyGeneratorStrategy
{
    /** @var array<string, string> */
    private array $vapid;

    public function __construct()
    {
        $this->vapid = [];
    }
    /**
     * @return array<string, string>
     */
    public function generateACoupleOfKey(): array
    {
        $this->vapid = VAPID::createVapidKeys();
        return $this->vapid;
    }

    /**
     * @return array<string, string>
     */
    public function getVAPIDKeys(): array
    {
        if (!isset($this->vapid)) {
            throw new \RuntimeException("VAPID keys have not been initialized.");
        }
        return $this->vapid;
    }


    public function __toString(): string
    {
        $json = json_encode($this->vapid);
        return $json !== false ? $json : '';
    }
}
