<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Service\ResponseInterface;

class ResponseLib implements ResponseInterface
{
    public function __construct(public readonly string $message)
    {
    }
}
