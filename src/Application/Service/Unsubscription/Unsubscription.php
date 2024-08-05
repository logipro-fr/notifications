<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\Subscriber;
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
        $subscriber = $this->deleteSubscriber($request);
        $this->repository->delete($subscriber);

        $this->response = new UnsubscriptionResponse(
            $subscriber->getEndpoint(),
            $subscriber->getExpirationTime(),
            $subscriber->getKeys()->toArray()
        );
    }

    private function deleteSubscriber(UnsubscriptionRequest $request): Subscriber
    {
        $endpoint = new Endpoint($request->endpoint);
        $subscriber = $this->repository->findById($endpoint);

        return $subscriber;
    }

    public function getResponse(): UnsubscriptionResponse
    {
        return $this->response;
    }
}
