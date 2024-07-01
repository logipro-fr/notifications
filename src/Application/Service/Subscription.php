<?php

namespace Notifications\Application\Service;

use Notifications\Domain\Entity\Subscriber\Status;
use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Subscriber\SubscriberRepositoryInterface;

class Subscription
{
    private SubscriptionResponse $response;
    public function __construct(
        private ApiInterface $api,
        private SubscriberRepositoryInterface $repository,
        private string $endpoint = ""
    ) {
    }
    public function execute(SubscriptionRequest $request): void
    {
        $subscriber = $this->createSubscriber($request);
        $apiResponse = $this->api->subscriberApiRequest($subscriber);
        $this->repository->add($subscriber);
        $subscriber->setStatus(Status::SUBSCRIBED);

        $this->response = new SubscriptionResponse(
            $apiResponse->endpoint,
            "granted",
        );
    }

    private function createSubscriber(SubscriptionRequest $request): Subscriber
    {
        $subscriberFactory = new SubscriberFactory();
        return $subscriberFactory->buildSubscriberFromRequest($request, $this->endpoint);
    }

    public function getResponse(): SubscriptionResponse
    {
        return $this->response;
    }
}
