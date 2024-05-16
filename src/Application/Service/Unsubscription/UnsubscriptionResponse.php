<?php

namespace Notifications\Application\Service\Unsubscription;

class UnsubscriptionResponse
{
    public function __construct(public readonly string $message)
    {
    }
}
