<?php

namespace Notifications\Domain\Model\Notification;

class Title
{
    public function __construct(private string $content)
    {
        if (empty($this->content)) {
            $this->content = "Default Title";
        } else {
            $this->content = $content;
        }
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
