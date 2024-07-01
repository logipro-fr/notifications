<?php

namespace Notifications\Domain\Entity\Subscriber;

class ExpirationTime
{
    public function __construct(private string $id = "")
    {
        if (empty($this->id)) {
            $this->id = uniqid("ExpirationTime");
        }
    }

    public function equals(ExpirationTime $time): bool
    {
        if ($this->id === $time->id) {
            return true;
        }
        return false;
    }
    public function __toString(): string
    {
        return $this->id;
    }
}
