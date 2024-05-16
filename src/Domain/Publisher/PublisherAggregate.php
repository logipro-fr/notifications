<?php

namespace Notifications\Domain\Publisher;


class PublisherAggregate
{
    private Publisher $publisher;
    private array $subscribers;

    public function __construct(Publisher $publisher, array $subscribers)
    {
        $this->publisher = $publisher;
        $this->subscribers = $subscribers;
    }

}
