<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Service\RequestInterface;

class RequestLib implements RequestInterface
{
    public function __construct(public readonly string $prompt)
    {
    }
}
