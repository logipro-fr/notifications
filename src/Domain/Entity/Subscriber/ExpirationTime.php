<?php

namespace Notifications\Domain\Entity\Subscriber;

class ExpirationTime
{
    private string $value;

    public function __construct(string $value = "")
    {
        if ($value === null || $value === "") {
            $this->value = "";
        } else {
            $this->value = $value;
        }
    }

    public function equals(ExpirationTime $time): bool
    {
        return $this->value === $time->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
