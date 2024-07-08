<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Subscriber;

interface ApiInterface
{
    public function subscriberApiRequest(Subscriber $subscriber): ProviderResponse;
}
