<?php

namespace Notifications\Domain;

use Notifications\Domain\Exceptions\BadDataClassException;
use Notifications\Domain\Publisher\Publisher;

class Subscriber
{
    /** @var array<mixed> */
    protected array $subscriberId;

    ///Constructor


    /** @param Publisher $name */
    /** @param array<mixed> $userAddress */
    /** @return string */
    public function subscribe(Publisher $name, array $userAddress): string
    {
        $message = "subscribed";
        $this->subscriberId = $userAddress;
        return $message;
    }

    public function getSubscriberId(): array
    {
        return $this->subscriberId;
    }

}
