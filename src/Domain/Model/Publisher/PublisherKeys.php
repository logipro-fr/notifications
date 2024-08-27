<?php

namespace Notifications\Domain\Model\Publisher;

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
     * Generate a new pair of VAPID keys.
     * @return array<string, string>
     */
    public function generateACoupleOfKey(): array
    {
        $this->vapid = VAPID::createVapidKeys();
        return $this->vapid;
    }

    /**
     * Get the current VAPID keys.
     * @return array<string, string>
     */
    public function getVAPIDKeys(): array
    {
        return $this->vapid;
    }
}
