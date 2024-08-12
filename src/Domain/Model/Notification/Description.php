<?php

namespace Notifications\Domain\Model\Notification;

class Description
{
    public function __construct(private string $content)
    {
        if (empty($this->content)) {
            $this->content = "";
        }
        else
        {
            $this->content = $content;
        }
    }

    public function __toString(): string
    {
        return $this->content;
    }

}
