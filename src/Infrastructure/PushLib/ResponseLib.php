<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Interface\ResponseInterface;

class ResponseLib implements ResponseInterface
{
    public function __construct(public readonly string $message)
    {
    }
}
