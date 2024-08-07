<?php

namespace Notifications\Application\Service\Unsubscription;

use Notifications\Application\Service\SubscriberFactory;
use Notifications\Domain\Model\Publisher\Publisher;
use Notifications\Domain\Model\Subscriber\Endpoint;
use Notifications\Domain\Model\Subscriber\ExpirationTime;
use Notifications\Domain\Model\Subscriber\Keys;
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
        $subscriberFactory = new SubscriberFactory();
        $subscriber = $subscriberFactory->buildSubscriberForDelete($request);
        if ($subscriber !== null) {
            $this->repository->delete($subscriber);
            $this->repository->flush;
            $this->response = new UnsubscriptionResponse(
                $subscriber->getEndpoint(),
                $subscriber->getExpirationTime(),
                $subscriber->getKeys()->toArray()
            );
        } else {
            throw new \Exception('Subscriber not found.');
        }
    }


    public function getResponse(): UnsubscriptionResponse
    {
        return $this->response;
    }
}
