<?php

namespace Notifications\Application\Service\Subscription;

use Notifications\Application\Service\SubscriberFactory;
use Notifications\Domain\Model\Subscriber\Subscriber;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;

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
