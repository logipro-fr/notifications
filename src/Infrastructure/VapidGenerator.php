<?php

namespace Notifications\Infrastructure;

use Minishlink\WebPush\VAPID;
use Notifications\Domain\KeyGeneratorStrategy;

class VapidGenerator implements KeyGeneratorStrategy
{
    /**
     * @return array<mixed>
     */
    public function generateACoupleOfKey(): array
    {
        var_dump(VAPID::createVapidKeys());
        return VAPID::createVapidKeys();
    }
}
