<?php

namespace Notifications\Application\Service;

class ProviderResponse
{
    public function __construct(
        public readonly bool $success,
    ) {
    }
}
