<?php

namespace Notifications\Domain\Model\Subscriber;

use Notifications\Domain\Model\Publisher\Publisher;

class Subscriber
{
    private Publisher $publisher;
    private Endpoint $endpoint;
    private Keys $keys;
    private ExpirationTime $expirationTime;
    
    private Status $status;

    public function __construct(
        Endpoint $endpoint,
        Keys $keys,
        ExpirationTime $time,
        Publisher $publisher
    ) {
        $this->endpoint = $endpoint;
        $this->keys = $keys;
        $this->expirationTime = $time;
        $this->status = Status::SUBSCRIBED;
        $this->publisher = $publisher;
    }

    /**
     * {@inheritDoc}
     */
    public function getEndpoint(): Endpoint
    {
        return $this->endpoint;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeys(): Keys
    {
        return $this->keys;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpirationTime(): ExpirationTime
    {
        return $this->expirationTime;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getPublisher(): Publisher
    {
        return $this->publisher;
    }
}
