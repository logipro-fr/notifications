<?php

namespace Notifications\Domain\Model\Notification;

class Icon
{
    public function __construct(private string $imageName)
    {
        if (empty($this->imageName)) {
            $this->imageName = "";
        } else {
            $this->imageName = $imageName;
        }
    }

    public function __toString(): string
    {
        return $this->imageName;
    }
}
