<?php

namespace Notifications\Infrastructure\PushLib;

use Notifications\Application\Service\RequestInterface;
use Notifications\Application\Service\ResponseInterface;

interface PushLibInterface
{
    public function request(RequestInterface $request): ResponseInterface;
}
