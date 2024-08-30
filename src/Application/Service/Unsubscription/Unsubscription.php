<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Application\Service\SubscriberFactory;
use Notifications\Domain\Model\Subscriber\SubscriberRepositoryInterface;

class Unsubscription
{
    private UnsubscriptionResponse $response;
    private SubscriberRepositoryInterface $repository;

    public function __construct(
        SubscriberRepositoryInterface $repository,
    ) {
        $this->repository = $repository;
    }

    public function execute(UnsubscriptionRequest $request): void
    {
        $subscriberFactory = new SubscriberFactory();
        $subscriber = $subscriberFactory->buildSubscriberForDelete($request);
        if ($subscriber !== null) {
            $this->repository->delete($subscriber);
            $this->response = new UnsubscriptionResponse(
                $subscriber->getEndpoint(),
                $subscriber->getExpirationTime(),
                $subscriber->getKeys()->toArray()
            );
        }
    }

    public function getResponse(): UnsubscriptionResponse
    {
        return $this->response;
    }
}
