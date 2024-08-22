<?php

namespace Notifications\Domain\Model\Notification;

class Action
{
    public function __construct(private string $url)
    {
        $this->url = $url ?: '';
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
