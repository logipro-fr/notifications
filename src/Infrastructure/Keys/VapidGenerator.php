<?php

namespace Notifications\Infrastructure\Keys;

use Minishlink\WebPush\VAPID;
use Notifications\Domain\Services\KeyGeneratorStrategy;

class VapidGenerator implements KeyGeneratorStrategy
{
    /**
     * @return array<string>
     */
    public function generateACoupleOfKey(): array
    {
        return VAPID::createVapidKeys();
    }
}
