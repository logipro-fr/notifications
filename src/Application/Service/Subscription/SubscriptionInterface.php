<?php

namespace Notifications\Application\Service\Subscription;

interface SubscriptionInterface
{
    public function getEndpoint(): string;

    public function getPublicKey(): ?string;

    public function getAuthToken(): ?string;

    public function getContentEncoding(): ?string;
}

