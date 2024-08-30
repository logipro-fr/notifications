<?php

namespace Notifications\Domain\Services;

class StatusClient
{
    private bool $valueClient;

    public function setValue(bool $value): void
    {
        $this->valueClient = $value;
    }

    public function getValue(): bool
    {
        return $this->valueClient;
    }
}
