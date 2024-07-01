<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Provider\ProviderResponse;

interface ApiInterface
{
    public function subscriberApiRequest(Subscriber $subscriber): ProviderResponse;
}
