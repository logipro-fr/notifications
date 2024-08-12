<?php

namespace Notifications\Domain\Model\Notification;

class Action
{
    public function __construct(private string $url)
    {
        if (empty($this->url)) {
            $this->url = "";
        }
        else
        {
            $this->url = $url;
        }
    }

    public function __toString(): string
    {
        return $this->url;
    }

}
