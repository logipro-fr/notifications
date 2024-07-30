<?php

namespace Notifications\Application\Service\Unsubscription;

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
            "success"
        );
    }

    private function deleteSubscriber(UnsubscriptionRequest $request): Subscriber
    {
        $subscriber = $this->repository->findById($request->endpoint);

        return $subscriber;
    }

    public function getResponse(): UnsubscriptionResponse
    {
        return $this->response;
    }
}
