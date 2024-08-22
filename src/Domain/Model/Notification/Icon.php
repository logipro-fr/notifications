<?php

namespace Notifications\Domain\Model\Notification;

class Icon
{
    public function __construct(private string|null $imageName)
    {
        $this->imageName = $imageName ?: '';
    }

    public function getIcon(): string|null
    {
        return $this->imageName;
    }
}
