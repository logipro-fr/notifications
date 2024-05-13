<?php

namespace Notifications\Infrastructure\Lib;

use Notifications\Application\Service\ResponseInterface;

class ResponseLib implements ResponseInterface
{
    public function __construct(public readonly string $message)
    {
    }
}
