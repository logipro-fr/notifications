<?php

namespace Notifications\Domain\Services;

class AuthorizationStatus
{
    private bool $isAuthorized;

    public function __construct(bool $isAuthorized)
    {
        $this->isAuthorized = $isAuthorized;
    }

    public function isAuthorized(): bool
    {
        return $this->isAuthorized;
    }

    public function setAuthorization(bool $isAuthorized): void
    {
        $this->isAuthorized = $isAuthorized;
    }
}
