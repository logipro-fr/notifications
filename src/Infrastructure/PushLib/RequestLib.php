<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Interface\RequestInterface;

class RequestLib implements RequestInterface
{
    public function __construct(public readonly string $message)
    {
    }
}
