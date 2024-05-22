<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Interface\RequestInterface;
use Notifications\Application\Interface\ResponseInterface;

interface PushLibInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}
