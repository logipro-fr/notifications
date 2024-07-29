<?php

namespace Notifications\Domain\Model\Subscriber;

use Notifications\Domain\Exceptions\EmptySubscriberContentException;

class Endpoint
{
    public function __construct(private string $url)
    {
        if (empty($this->url)) {
            throw new EmptySubscriberContentException(
                EmptySubscriberContentException::MESSAGE,
                EmptySubscriberContentException::ERROR_CODE
            );
        }
        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

    public function equals(Endpoint $endpoint): bool
    {
        if ($this->url === $endpoint->url) {
            return true;
        }
        return false;
    }
}
