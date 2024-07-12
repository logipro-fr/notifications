<?php

namespace Notifications\Domain\Entity\Subscriber;

class ExpirationTime
{
    private string $value;

    public function __construct(string $value = "")
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
