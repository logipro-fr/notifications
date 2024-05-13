<?php

namespace Notifications\Application\Service\Subscription;

use Notifications\Domain\KeyGeneratorStrategy;
use Notifications\Domain\Publisher;

class Subscription
{
    private SubscriptionResponse $response;

    public function __construct(private KeyGeneratorStrategy $keyGenerator)
    {
    }

    public function execute(SubscriptionRequest $request): void
    {
        $publisher = new Publisher($request->url, $this->keyGenerator);

        $this->response = new SubscriptionResponse($publisher->getPublicKey());
    }

    public function getResponse(): SubscriptionResponse
    {
        return $this->response;
    }
}
