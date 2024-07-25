<?php

namespace Notifications\Application\Service;

class PermissionRequest
{
    public function __construct(
        public readonly bool $status,
    ) {
    }
}

