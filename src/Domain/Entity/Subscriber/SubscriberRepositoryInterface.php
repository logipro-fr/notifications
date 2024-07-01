<?php

namespace Notifications\Domain\Entity\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;

interface SubscriberRepositoryInterface
{
    public function add(Subscriber $subscriber): void;
    public function findById(Endpoint $searchId): Subscriber;
}
