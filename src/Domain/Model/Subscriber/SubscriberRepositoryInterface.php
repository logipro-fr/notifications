<?php

namespace Notifications\Domain\Model\Subscriber;

use Notifications\Domain\Model\Subscriber\Subscriber;

interface SubscriberRepositoryInterface
{
    public function add(Subscriber $subscriber): void;
    public function findById(Endpoint $searchId): ?Subscriber;
    public function delete(Subscriber $subscriber): void;
}
