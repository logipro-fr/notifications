<?php

namespace Notifications\Domain\Entity\Subscriber;

use Doctrine\ORM\Mapping as ORM;
use Notifications\Domain\Entity\Publisher\Publisher;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscribers")
 */
class Subscriber
{
    private Publisher $publisher;
    /**
     * @ORM\Embedded(class="endpoint")
     * @ORM\Column(type="text", nullable=false)
     */
    private Endpoint $endpoint;

    /**
     * @ORM\Embedded(class="Keys")
     * @ORM\Column(name="keys", type="json")
     */
    private Keys $keys;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ExpirationTime $expirationTime;

    /**
     * @ORM\Column(type="string", enumType="Status")
     */
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
