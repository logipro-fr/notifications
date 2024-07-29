<?php

namespace Notifications\Domain\Model\Subscriber;

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
