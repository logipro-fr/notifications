<?php

namespace Notifications\Application\Service;

class PermissionResponse
{
    public function __construct(
        public readonly bool $status,
    ) {
    }
}
