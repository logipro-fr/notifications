<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Status;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;

class Subscription
{
    private SubscriptionResponse $response;
    private SubscriberRepositoryInterface $repository;

    public function __construct(
        SubscriberRepositoryInterface $repository,
    ) {
        $this->repository = $repository;
    }

    public function execute(SubscriptionRequest $request): void
    {
        $subscriber = $this->createSubscriber($request);

        $this->repository->add($subscriber);
        $subscriber->setStatus(Status::SUBSCRIBED);

        $this->response = new SubscriptionResponse(
            $subscriber->getEndpoint(),
            $subscriber->getExpirationTime(),
            $subscriber->getKeys()->toArray()
        );
    }

    private function createSubscriber(SubscriptionRequest $request): Subscriber
    {
        $subscriberFactory = new SubscriberFactory();
        $subscriber = $subscriberFactory->buildSubscriberFromRequest($request);

        return $subscriber;
    }

    public function getResponse(): SubscriptionResponse
    {
        return $this->response;
    }
}
