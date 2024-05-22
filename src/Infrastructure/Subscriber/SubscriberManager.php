<?php

namespace Notifications\Infrastructure\Subscriber;

use Notifications\Domain\Entity\Subscriber\Subscriber;
use Notifications\Domain\Entity\Publisher\Publisher;

class SubscriberManager
{
    private const SEPARATOR = ", ";
    private const POINT_SEPARATOR = "; ";
    private const SUB_TITLE_LIST = "Subscribers: ";
    private SubscriberRepository $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    public function addSubscriber(Subscriber $subscriber): Subscriber
    {
        return $this->subscriberRepository->add($subscriber);
    }

    public function removeSubscriber(Subscriber $subscriber): void
    {
        $this->subscriberRepository->remove($subscriber);
    }
    /**
     * @return array<Subscriber>
     */
    public function getSubscribers(): array
    {
        return $this->subscriberRepository->getAll();
    }

    public function __toString(): string
    {
        $subscriberList = array_map(function (Subscriber $subscriber) {
            $subscriberId = $subscriber->getSubscriberId();
            $flattenedIds = [];
            foreach ($subscriberId as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        $flattenedIds[] = $subValue;
                    }
                } else {
                    $flattenedIds[] = $value;
                }
            }
            return implode(self::SEPARATOR, $flattenedIds);
        }, $this->subscriberRepository->getAll());

        return self::SUB_TITLE_LIST . implode(self::POINT_SEPARATOR, array_filter($subscriberList));
    }

    //public function notifySubscribers(Publisher $publisher): void
    //{
    //    foreach ($this->subscriberRepository->getAll() as $subscriber) {
    //        $subscriber->subscribe($publisher, $subscriber->getSubscriberId());
    //    }
    //}
}
