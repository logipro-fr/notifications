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
        $publicKey = $publisher->getPublicKey();
        if (!is_string($publicKey)) {
            $publicKey = $this->generateFallbackKey();
        }
        $this->response = new SubscriptionResponse($publicKey);
    }

    public function getResponse(): SubscriptionResponse
    {
        return $this->response;
    }

    private function generateFallbackKey(): string
    {
        return 'fallback_key';
    }
}
