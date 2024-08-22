<?php

namespace Notifications\Domain\Model\Notification;

class Description
{
    public function __construct(private string $content)
    {
        $this->content = $content ?: '';
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
