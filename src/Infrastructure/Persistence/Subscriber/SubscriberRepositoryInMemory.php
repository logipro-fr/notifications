<?php

namespace Notifications\Infrastructure\Persistence\Subscriber;

use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Exceptions\SubscriberNotFoundException;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;

class SubscriberRepositoryInMemory implements SubscriberRepositoryInterface
{
    /** @var Subscriber[] */
    private array $subscribers = [];
    private int $errorCode = 400;

    public function add(Subscriber $subscriber): void
    {
        $this->subscribers[$subscriber->getEndpoint()->__toString()] = $subscriber;
    }

    public function delete(Subscriber $subscriber): void
    {
        $this->subscribers[$subscriber->getEndpoint()->__toString()]="";
    }

    public function findById(Endpoint $searchId): Subscriber
    {
        $endpointString = $searchId->__toString();
        if (!isset($this->subscribers[$endpointString])) {
            throw new SubscriberNotFoundException(
                sprintf("Error can't find the endpoint %s", $endpointString),
                $this->errorCode
            );
        }
        return $this->subscribers[$endpointString];
    }
}
