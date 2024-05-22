<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Infrastructure\Subscriber\SubscriberRepository;

class InMemorySubscriberRepository implements SubscriberRepository
{
    /** @var array<Subscriber> */
    private array $subscribers;

    public function __construct()
    {
        $this->subscribers = [];
    }

    public function add(Subscriber $subscriber): Subscriber
    {
        $this->subscribers[] = $subscriber;
        return $subscriber;
    }

    public function remove(Subscriber $subscriber): void
    {
        $index = array_search($subscriber, $this->subscribers, true);
        if ($index !== false) {
            unset($this->subscribers[$index]);
        }
    }

     /** @return array<Subscriber> */
    public function getAll(): array
    {
        return $this->subscribers;
    }
}
