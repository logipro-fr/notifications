<?php

namespace Notifications\Application\Service\OptIn;

class PermissionResponse
{
    public function __construct(
        public readonly bool $status,
    ) {
    }
}
