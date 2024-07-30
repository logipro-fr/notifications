<?php

namespace Notifications\Application\Service\OptIn;

class PermissionRequest
{
    public function __construct(
        public readonly bool $status,
    ) {
    }
}
