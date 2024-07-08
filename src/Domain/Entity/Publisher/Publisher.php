<?php

namespace Notifications\Domain\Entity\Publisher;

use Minishlink\WebPush\WebPush;
use Notifications\Domain\Services\KeyGeneratorStrategy;
use Notifications\Domain\Entity\Subscriber\Subscriber;

class Publisher
{
    private string $name;

    /** @var array<Subscriber> */
    protected array $subscribers = [];


    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getTargetName(): string
    {
        return $this->name;
    }
}
