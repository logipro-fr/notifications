<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;

interface SubscriberRepository
{
    public function add(Subscriber $subscriber): Subscriber;
    public function remove(Subscriber $subscriber): void;
    /**
     * @return array<Subscriber>
     */
    public function getAll(): array;
}
